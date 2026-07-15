/**
 * Supabase CRM writer.
 *
 * Inserts a lead into the intake CRM so it shows up on the pipeline board
 * immediately, and captures abandoned inquiries so the team can follow up.
 * Supabase is the system of record for pre-enrollment; Google Sheets is a
 * best-effort mirror (see api/inquiry.ts).
 *
 * A completed lead writes THREE rows (the app reads the timeline from
 * client_events and the open follow-up from follow_ups):
 *   1. clients        — the card (critical). `data` also embeds the timeline +
 *                       follow-up as a fallback for a not-yet-backfilled read.
 *   2. client_events  — the "website inquiry" timeline entry (best-effort).
 *   3. follow_ups     — an open "call them back" task (best-effort).
 * Before inserting, we DEDUP on email / phone: a repeat submission is logged on
 * the existing card instead of creating a second one ("one row per client").
 *
 * Partials (abandoned forms) upsert into form_partials, keyed by session_id;
 * on a full submit we mark the matching partial converted.
 *
 * Config: SUPABASE_URL + SUPABASE_SERVICE_ROLE_KEY (server-only; bypasses RLS).
 * Behavior: 5s timeout; throws on not-configured or a failed clients insert so
 * the caller falls back to Sheets; never logs PII.
 */
import type { Lead, PartialLead } from "./validate.js";
import { logger } from "./logger.js";

const REQUEST_TIMEOUT_MS = 5000;

function isConfigured(): boolean {
  return !!(process.env.SUPABASE_URL && process.env.SUPABASE_SERVICE_ROLE_KEY);
}
function baseUrl(): string {
  return process.env.SUPABASE_URL!.replace(/\/$/, "");
}
function key(): string {
  return process.env.SUPABASE_SERVICE_ROLE_KEY!;
}

/** "+19195550142" -> "919-555-0142" for display parity with existing records. */
function formatUsPhone(e164: string): string {
  const digits = e164.replace(/\D/g, "");
  const ten = digits.length === 11 && digits.startsWith("1") ? digits.slice(1) : digits;
  if (ten.length === 10) return `${ten.slice(0, 3)}-${ten.slice(3, 6)}-${ten.slice(6)}`;
  return e164;
}

// how_did_you_hear (form enum) -> the CRM's Source vocabulary. Coarse by nature;
// UTMs carry the real attribution now.
const SOURCE_MAP: Record<string, string> = {
  Google: "Google",
  Referral: "Provider referral",
  Doctor: "Provider referral",
  School: "School counselor",
  Other: "Other",
};

function inquiryDate(lead: Lead): string {
  return lead.submitted_at.slice(0, 10); // YYYY-MM-DD
}
function timelineBody(lead: Lead): string {
  const bits = [`interested in ${lead.program_interest}`];
  if (lead.best_time_to_call) bits.push(`best time ${lead.best_time_to_call}`);
  return `Website inquiry — ${lead.name}, teen age ${lead.teen_age}, ${bits.join(", ")}.`;
}
function followUpNote(lead: Lead): string {
  return `Call ${lead.name.split(/\s+/)[0]} back — new website inquiry`;
}

/**
 * Map a `Lead` to a row in the CRM `clients` table.
 *
 * The form collects one name (parent or teen — the pre-screen call clarifies),
 * so it goes in `name`; `guardian` stays blank until then. `program` (the
 * assigned program) is left blank for staff to set at pre-screen — we only
 * record the family's stated `programInterest`. `email` is lowercased and a
 * normalized `phoneRaw` is stored so future submissions can dedup exactly.
 */
export function buildClientRecord(
  lead: Lead,
  id: number = Date.now()
): { id: number; data: Record<string, unknown> } {
  const date = inquiryDate(lead);
  const source = lead.how_did_you_hear ? SOURCE_MAP[lead.how_did_you_hear] ?? "Other" : "Other";
  const isTest = lead.name.trim().toLowerCase().startsWith("test");

  const data: Record<string, unknown> = {
    id,
    name: lead.name,
    guardian: "",
    age: lead.teen_age,
    program: "",
    programInterest: lead.program_interest,
    stage: "inquiry",
    origin: "Web",
    source,
    status: "open",
    phone: formatUsPhone(lead.phone),
    phoneRaw: lead.phone,
    email: lead.email.toLowerCase(),
    prefContact: "Phone",
    bestTime: lead.best_time_to_call ?? "",
    insurance: lead.insurance || "—",
    zip: lead.zip || "—",
    location: "—",
    owner: "",
    needs: lead.notes || "",
    notes: "",
    howHeard: lead.how_did_you_hear || "Website inquiry form",
    followUp: { due: date, assignee: "", note: followUpNote(lead) },
    added: date,
    stageSince: date,
    isTest,
    timeline: [{ t: "form", who: "Website", when: date, body: timelineBody(lead) }],
    leadId: lead.lead_id,
    sessionId: lead.session_id ?? null,
  };
  // Attribution — only when present, so cards stay clean.
  if (lead.utm_source) data.utmSource = lead.utm_source;
  if (lead.utm_medium) data.utmMedium = lead.utm_medium;
  if (lead.utm_campaign) data.utmCampaign = lead.utm_campaign;
  if (lead.referrer) data.referrer = lead.referrer;

  return { id, data };
}

/** A `client_events` row — the "website inquiry" timeline entry. */
export function buildClientEvent(lead: Lead, clientId: number): Record<string, unknown> {
  return {
    client_id: clientId,
    type: "form",
    body: timelineBody(lead),
    actor_name: "Website",
    meta: { when: inquiryDate(lead), source: "website_form", lead_id: lead.lead_id },
  };
}

/** A `follow_ups` row — the open "call them back" task. */
export function buildFollowUp(lead: Lead, clientId: number): Record<string, unknown> {
  return {
    client_id: clientId,
    due: inquiryDate(lead),
    assignee: "",
    note: followUpNote(lead),
    status: "open",
    created_by: "Website",
  };
}

/** A `client_events` row logged when a repeat submission matches an existing client. */
export function buildRepeatEvent(lead: Lead, clientId: number): Record<string, unknown> {
  const extra = lead.notes ? ` Note: ${lead.notes}` : "";
  return {
    client_id: clientId,
    type: "form",
    body: `Repeat website inquiry — interested in ${lead.program_interest}.${extra}`,
    actor_name: "Website",
    meta: { when: inquiryDate(lead), source: "website_form", lead_id: lead.lead_id, dedup: true },
  };
}

/** A `form_partials` row — a snapshot of an abandoned form, upserted by session. */
export function buildPartialRow(partial: PartialLead): Record<string, unknown> {
  const row: Record<string, unknown> = {
    session_id: partial.session_id,
    updated_at: new Date().toISOString(),
  };
  const copy: (keyof PartialLead)[] = [
    "name", "email", "phone", "teen_age", "program_interest", "best_time_to_call",
    "how_did_you_hear", "zip", "insurance", "notes",
    "utm_source", "utm_medium", "utm_campaign", "referrer",
  ];
  for (const k of copy) if (partial[k] != null) row[k] = partial[k];
  return row;
}

async function sbFetch(
  method: string,
  path: string,
  opts: { body?: unknown; prefer?: string } = {}
): Promise<Response> {
  const controller = new AbortController();
  const timeout = setTimeout(() => controller.abort(), REQUEST_TIMEOUT_MS);
  try {
    const headers: Record<string, string> = {
      apikey: key(),
      Authorization: `Bearer ${key()}`,
      Accept: "application/json",
    };
    if (opts.body !== undefined) headers["Content-Type"] = "application/json";
    if (opts.prefer) headers.Prefer = opts.prefer;
    return await fetch(`${baseUrl()}/rest/v1/${path}`, {
      method,
      headers,
      body: opts.body !== undefined ? JSON.stringify(opts.body) : undefined,
      signal: controller.signal,
    });
  } catch (err) {
    const aborted = (err as Error)?.name === "AbortError";
    throw new Error(aborted ? "SUPABASE_TIMEOUT" : "SUPABASE_NETWORK_ERROR");
  } finally {
    clearTimeout(timeout);
  }
}

async function insertRow(table: string, row: unknown): Promise<void> {
  const res = await sbFetch("POST", table, { body: row, prefer: "return=minimal" });
  if (!res.ok) throw new Error(`SUPABASE_HTTP_${res.status}`);
}

/**
 * Best-effort dedup: find an existing client whose email or normalized phone
 * matches this lead. Returns the client id or null. Any error → null (we'd
 * rather risk a rare duplicate than drop a lead on a flaky lookup).
 */
async function findExistingClient(lead: Lead): Promise<number | null> {
  try {
    const clauses: string[] = [];
    if (lead.email) clauses.push(`data->>email.eq.${encodeURIComponent(lead.email.toLowerCase())}`);
    if (lead.phone) clauses.push(`data->>phoneRaw.eq.${encodeURIComponent(lead.phone)}`);
    if (!clauses.length) return null;
    const res = await sbFetch("GET", `clients?select=id&or=(${clauses.join(",")})&limit=1`);
    if (!res.ok) return null;
    const rows = (await res.json()) as Array<{ id: number }>;
    return rows.length ? rows[0]!.id : null;
  } catch {
    return null;
  }
}

/** Mark the partial for this session converted (best-effort — never throws). */
export async function markPartialConverted(sessionId?: string): Promise<void> {
  if (!sessionId || !isConfigured()) return;
  try {
    await sbFetch("PATCH", `form_partials?session_id=eq.${encodeURIComponent(sessionId)}`, {
      body: { status: "converted", updated_at: new Date().toISOString() },
      prefer: "return=minimal",
    });
  } catch {
    /* best-effort */
  }
}

export async function sendToSupabase(lead: Lead): Promise<void> {
  if (!isConfigured()) {
    logger.info("Supabase not configured — skipping", { lead_id: lead.lead_id });
    throw new Error("SUPABASE_NOT_CONFIGURED");
  }

  // 0. Dedup — attach a repeat inquiry to the existing card instead of a new one.
  const existingId = await findExistingClient(lead);
  if (existingId != null) {
    try {
      await insertRow("client_events", buildRepeatEvent(lead, existingId));
    } catch (err) {
      logger.warn("Repeat-inquiry event insert failed", {
        lead_id: lead.lead_id,
        error_code: (err as Error).message,
      });
    }
    logger.info("Deduped — repeat inquiry attached to existing client", { lead_id: lead.lead_id });
    await markPartialConverted(lead.session_id);
    return;
  }

  // 1. The client row is the lead. If this fails, the caller falls back to Sheets.
  const id = Date.now();
  try {
    await insertRow("clients", buildClientRecord(lead, id));
  } catch (err) {
    logger.error("Supabase clients insert failed", {
      lead_id: lead.lead_id,
      error_code: (err as Error).message,
    });
    throw err;
  }
  logger.info("Lead written to Supabase", { lead_id: lead.lead_id });

  // 2 + 3. Timeline + follow-up rows for the append-only tables (best-effort).
  const labels = ["client_events", "follow_ups"];
  const extras = await Promise.allSettled([
    insertRow("client_events", buildClientEvent(lead, id)),
    insertRow("follow_ups", buildFollowUp(lead, id)),
  ]);
  extras.forEach((r, i) => {
    if (r.status === "rejected") {
      logger.warn(`Supabase ${labels[i]} insert failed`, {
        lead_id: lead.lead_id,
        error_code: (r.reason as Error)?.message,
      });
    }
  });

  await markPartialConverted(lead.session_id);
}

/** Upsert an abandoned-form snapshot into form_partials (keyed by session_id). */
export async function sendPartialToSupabase(partial: PartialLead): Promise<void> {
  if (!isConfigured()) {
    throw new Error("SUPABASE_NOT_CONFIGURED");
  }
  const res = await sbFetch("POST", "form_partials?on_conflict=session_id", {
    body: buildPartialRow(partial),
    prefer: "resolution=merge-duplicates,return=minimal",
  });
  if (!res.ok) throw new Error(`SUPABASE_HTTP_${res.status}`);
  logger.info("Partial written to Supabase", { lead_id: partial.lead_id });
}
