import { describe, it, expect, vi, beforeEach, afterEach } from "vitest";
import {
  buildClientRecord,
  buildClientEvent,
  buildFollowUp,
  buildRepeatEvent,
  buildPartialRow,
  sendToSupabase,
  sendPartialToSupabase,
} from "../lib/supabase.js";
import type { Lead, PartialLead } from "../lib/validate.js";

const lead: Lead = {
  lead_id: "JT-test1234",
  submitted_at: "2026-07-15T10:00:00.000Z",
  name: "Jane Doe",
  email: "Jane@Example.com",
  phone: "+15551234567",
  teen_age: 14,
  program_interest: "IOP",
  best_time_to_call: "Morning",
  how_did_you_hear: "Google",
  zip: "27502",
  insurance: "Aetna",
  notes: "Some context.",
  utm_source: "google",
  utm_campaign: "iop_apex",
  session_id: "sess_abc",
};

describe("buildClientRecord", () => {
  it("maps a lead to the CRM inquiry shape using the typed values", () => {
    const { id, data } = buildClientRecord(lead, 1752573600000);
    expect(id).toBe(1752573600000);
    expect(data.name).toBe("Jane Doe"); // the name typed, not a placeholder
    expect(data.guardian).toBe(""); // clarified on the pre-screen call
    expect(data.age).toBe(14);
    expect(data.stage).toBe("inquiry");
    expect(data.origin).toBe("Web");
    expect(data.status).toBe("open");
    expect(data.email).toBe("jane@example.com"); // lowercased for dedup
    expect(data.phone).toBe("555-123-4567"); // formatted for display
    expect(data.phoneRaw).toBe("+15551234567"); // E.164 for exact dedup
    expect(data.zip).toBe("27502");
    expect(data.insurance).toBe("Aetna");
    expect(data.needs).toBe("Some context.");
  });

  it("keeps interest and assigned program separate", () => {
    const { data } = buildClientRecord(lead);
    expect(data.programInterest).toBe("IOP"); // what the family said
    expect(data.program).toBe(""); // staff assign at pre-screen — never derived
  });

  it("carries UTM attribution only when present", () => {
    expect(buildClientRecord(lead).data.utmSource).toBe("google");
    const { utm_source, utm_campaign, ...noUtm } = lead;
    expect(buildClientRecord(noUtm as Lead).data).not.toHaveProperty("utmSource");
  });

  it("flags a TEST submission", () => {
    expect(buildClientRecord({ ...lead, name: "Test Person" }).data.isTest).toBe(true);
    expect(buildClientRecord(lead).data.isTest).toBe(false);
  });

  it("leaves needs empty when no note was given", () => {
    const { notes, ...noNotes } = lead;
    expect(buildClientRecord(noNotes as Lead).data.needs).toBe("");
  });
});

describe("event / follow-up / repeat / partial builders", () => {
  it("builds a client_events row the app maps to a timeline entry", () => {
    const ev = buildClientEvent(lead, 42) as { client_id: number; type: string; actor_name: string };
    expect(ev.client_id).toBe(42);
    expect(ev.type).toBe("form");
    expect(ev.actor_name).toBe("Website");
  });

  it("builds an open follow_ups row due on the inquiry date", () => {
    const fu = buildFollowUp(lead, 42) as { client_id: number; due: string; status: string };
    expect(fu.due).toBe("2026-07-15");
    expect(fu.status).toBe("open");
  });

  it("builds a repeat-inquiry event flagged as a dedup", () => {
    const ev = buildRepeatEvent(lead, 7) as { client_id: number; body: string; meta: { dedup: boolean } };
    expect(ev.client_id).toBe(7);
    expect(ev.body).toContain("Repeat website inquiry");
    expect(ev.meta.dedup).toBe(true);
  });

  it("builds a form_partials row with only the fields present + updated_at", () => {
    const partial: PartialLead = {
      lead_id: "JT-P-x",
      submitted_at: "2026-07-15T10:00:00.000Z",
      session_id: "sess_abc",
      name: "Jane",
      phone: "555",
      program_interest: "PHP",
      utm_source: "google",
    };
    const row = buildPartialRow(partial) as Record<string, unknown>;
    expect(row.session_id).toBe("sess_abc");
    expect(row.name).toBe("Jane");
    expect(row.program_interest).toBe("PHP");
    expect(row.utm_source).toBe("google");
    expect(row).toHaveProperty("updated_at");
    expect(row).not.toHaveProperty("email"); // omitted when absent
    expect(row).not.toHaveProperty("how_did_you_hear");
  });
});

// Fetch mock: GET (dedup) returns `existing`; writes return 201 (or an override); PATCH 204.
function mockSb(opts: { existing?: unknown[]; status?: Record<string, number> } = {}) {
  const existing = opts.existing ?? [];
  const statusByTable = opts.status ?? {};
  return vi.fn(async (url: string, init?: { method?: string }) => {
    const method = (init?.method || "GET").toUpperCase();
    const table = new URL(url).pathname.split("/").pop()!;
    if (method === "GET") return new Response(JSON.stringify(existing), { status: 200 });
    const st = statusByTable[table] ?? (method === "PATCH" ? 204 : 201);
    return new Response(null, { status: st });
  });
}
function callsTo(mock: ReturnType<typeof mockSb>, method: string, table: string) {
  return mock.mock.calls.filter(([url, init]: [string, { method?: string }]) => {
    const m = (init?.method || "GET").toUpperCase();
    return m === method && new URL(url).pathname.endsWith(`/${table}`);
  });
}

describe("sendToSupabase", () => {
  const originalFetch = globalThis.fetch;
  const originalUrl = process.env.SUPABASE_URL;
  const originalKey = process.env.SUPABASE_SERVICE_ROLE_KEY;

  beforeEach(() => {
    process.env.SUPABASE_URL = "https://proj.supabase.co";
    process.env.SUPABASE_SERVICE_ROLE_KEY = "service-role-test-key";
  });
  afterEach(() => {
    globalThis.fetch = originalFetch;
    process.env.SUPABASE_URL = originalUrl;
    process.env.SUPABASE_SERVICE_ROLE_KEY = originalKey;
  });

  it("throws SUPABASE_NOT_CONFIGURED when env vars missing", async () => {
    delete process.env.SUPABASE_URL;
    delete process.env.SUPABASE_SERVICE_ROLE_KEY;
    await expect(sendToSupabase(lead)).rejects.toThrow("SUPABASE_NOT_CONFIGURED");
  });

  it("creates a new client (clients + events + follow_ups) when no match exists", async () => {
    const m = mockSb({ existing: [] });
    globalThis.fetch = m as unknown as typeof fetch;

    await sendToSupabase(lead);

    expect(callsTo(m, "POST", "clients")).toHaveLength(1);
    expect(callsTo(m, "POST", "client_events")).toHaveLength(1);
    expect(callsTo(m, "POST", "follow_ups")).toHaveLength(1);
    expect(callsTo(m, "PATCH", "form_partials")).toHaveLength(1); // markPartialConverted
    const clientsBody = JSON.parse(callsTo(m, "POST", "clients")[0]![1].body as string);
    expect(clientsBody.data.stage).toBe("inquiry");
    expect(clientsBody.data.name).toBe("Jane Doe");
    expect(clientsBody.data.program).toBe("");
  });

  it("dedups a repeat submission — logs a repeat event, no new client", async () => {
    const m = mockSb({ existing: [{ id: 999 }] });
    globalThis.fetch = m as unknown as typeof fetch;

    await sendToSupabase(lead);

    expect(callsTo(m, "POST", "clients")).toHaveLength(0); // no duplicate card
    const events = callsTo(m, "POST", "client_events");
    expect(events).toHaveLength(1);
    const body = JSON.parse(events[0]![1].body as string);
    expect(body.client_id).toBe(999);
    expect(body.meta.dedup).toBe(true);
  });

  it("throws SUPABASE_HTTP_<status> when the clients insert fails", async () => {
    const m = mockSb({ existing: [], status: { clients: 401 } });
    globalThis.fetch = m as unknown as typeof fetch;
    await expect(sendToSupabase(lead)).rejects.toThrow("SUPABASE_HTTP_401");
  });
});

describe("sendPartialToSupabase", () => {
  const originalFetch = globalThis.fetch;
  beforeEach(() => {
    process.env.SUPABASE_URL = "https://proj.supabase.co";
    process.env.SUPABASE_SERVICE_ROLE_KEY = "service-role-test-key";
  });
  afterEach(() => {
    globalThis.fetch = originalFetch;
  });

  const partial: PartialLead = {
    lead_id: "JT-P-x",
    submitted_at: "2026-07-15T10:00:00.000Z",
    session_id: "sess_abc",
    name: "Jane",
    email: "jane@example.com",
    phone: "555-123-4567",
  };

  it("upserts the partial into form_partials by session", async () => {
    const m = mockSb({});
    globalThis.fetch = m as unknown as typeof fetch;

    await sendPartialToSupabase(partial);

    const posts = callsTo(m, "POST", "form_partials");
    expect(posts).toHaveLength(1);
    const [url, init] = posts[0]!;
    expect(url).toContain("on_conflict=session_id");
    expect((init as { headers: Record<string, string> }).headers.Prefer).toContain("merge-duplicates");
    expect(JSON.parse((init as { body: string }).body).session_id).toBe("sess_abc");
  });

  it("throws on a non-2xx upsert", async () => {
    const m = mockSb({ status: { form_partials: 500 } });
    globalThis.fetch = m as unknown as typeof fetch;
    await expect(sendPartialToSupabase(partial)).rejects.toThrow("SUPABASE_HTTP_500");
  });
});
