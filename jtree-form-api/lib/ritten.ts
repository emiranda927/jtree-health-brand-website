/**
 * Ritten CRM client.
 *
 * Posts a lead as a "contact" to a Ritten endpoint (or any compatible
 * webhook relay — Keragon, Zapier, Make, etc.) using Bearer authentication.
 *
 * Configuration:
 *   RITTEN_API_URL  — full endpoint URL (e.g., https://api.ritten.io/v1/contacts
 *                     or https://hooks.keragon.com/abc123)
 *   RITTEN_API_KEY  — Bearer token. Sent as `Authorization: Bearer <key>`.
 *
 * Behavior:
 *   - 5s timeout. We never want to stall the form submission on a slow CRM.
 *   - Throws on non-2xx so the caller falls back to Google Sheets.
 *   - Never logs lead PII — only lead_id and HTTP status / error code.
 */
import type { Lead, PartialLead } from "./validate.js";
import { logger } from "./logger.js";

const REQUEST_TIMEOUT_MS = 5000;

function isConfigured(): boolean {
  return !!(process.env.RITTEN_API_KEY && process.env.RITTEN_API_URL);
}

/**
 * Build the JSON payload sent to Ritten.
 *
 * Maps the form's `Lead` shape to Ritten's "contact" semantics. If Ritten's
 * intake schema differs in production, adjust the field names here — the
 * surrounding HTTP plumbing, auth, and error handling stay unchanged.
 */
export function buildRittenPayload(lead: Lead): Record<string, unknown> {
  return {
    source: "website_form",
    stage: "lead",
    external_id: lead.lead_id,
    session_id: lead.session_id ?? null,
    submitted_at: lead.submitted_at,
    contact: {
      first_name: lead.parent_first_name,
      last_name: lead.parent_last_name,
      email: lead.parent_email,
      phone: lead.parent_phone,
      role: "parent_guardian",
    },
    patient: {
      age: lead.teen_age,
      relationship_to_contact: "child",
    },
    inquiry: {
      program_interest: lead.program_interest,
      best_time_to_call: lead.best_time_to_call,
      how_did_you_hear: lead.how_did_you_hear ?? null,
    },
  };
}

/**
 * Partial-inquiry payload. No `contact` block by design — partials carry no
 * PII. Admissions can match on `session_id` if the same visitor later submits
 * a full lead.
 */
export function buildRittenPartialPayload(
  partial: PartialLead
): Record<string, unknown> {
  return {
    source: "website_form",
    stage: "partial",
    external_id: partial.lead_id,
    session_id: partial.session_id,
    submitted_at: partial.submitted_at,
    inquiry: {
      teen_age: partial.teen_age ?? null,
      program_interest: partial.program_interest ?? null,
      best_time_to_call: partial.best_time_to_call ?? null,
      how_did_you_hear: partial.how_did_you_hear ?? null,
    },
    acquisition: {
      utm_source: partial.utm_source ?? null,
      utm_medium: partial.utm_medium ?? null,
      utm_campaign: partial.utm_campaign ?? null,
      referrer: partial.referrer ?? null,
    },
  };
}

export async function sendToRitten(lead: Lead): Promise<void> {
  if (!isConfigured()) {
    logger.info("Ritten not configured — skipping", { lead_id: lead.lead_id });
    throw new Error("RITTEN_NOT_CONFIGURED");
  }

  const url = process.env.RITTEN_API_URL!;
  const key = process.env.RITTEN_API_KEY!;
  const payload = buildRittenPayload(lead);

  const controller = new AbortController();
  const timeout = setTimeout(() => controller.abort(), REQUEST_TIMEOUT_MS);

  let response: Response;
  try {
    response = await fetch(url, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        Authorization: `Bearer ${key}`,
      },
      body: JSON.stringify(payload),
      signal: controller.signal,
    });
  } catch (err) {
    const aborted = (err as Error)?.name === "AbortError";
    logger.error("Ritten request failed", {
      lead_id: lead.lead_id,
      error_code: aborted ? "RITTEN_TIMEOUT" : "RITTEN_NETWORK_ERROR",
    });
    throw new Error(aborted ? "RITTEN_TIMEOUT" : "RITTEN_NETWORK_ERROR");
  } finally {
    clearTimeout(timeout);
  }

  if (!response.ok) {
    logger.error("Ritten returned non-2xx", {
      lead_id: lead.lead_id,
      error_code: `RITTEN_HTTP_${response.status}`,
    });
    throw new Error(`RITTEN_HTTP_${response.status}`);
  }

  logger.info("Lead sent to Ritten", { lead_id: lead.lead_id });
}

/**
 * Send a no-PII partial inquiry to the same Ritten endpoint with
 * `stage: 'partial'`. Throws on failure so the caller can fall back to
 * Google Sheets, matching the full-lead path.
 */
export async function sendPartialToRitten(partial: PartialLead): Promise<void> {
  if (!isConfigured()) {
    logger.info("Ritten not configured — skipping partial", { lead_id: partial.lead_id });
    throw new Error("RITTEN_NOT_CONFIGURED");
  }

  const url = process.env.RITTEN_API_URL!;
  const key = process.env.RITTEN_API_KEY!;
  const payload = buildRittenPartialPayload(partial);

  const controller = new AbortController();
  const timeout = setTimeout(() => controller.abort(), REQUEST_TIMEOUT_MS);

  let response: Response;
  try {
    response = await fetch(url, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        Authorization: `Bearer ${key}`,
      },
      body: JSON.stringify(payload),
      signal: controller.signal,
    });
  } catch (err) {
    const aborted = (err as Error)?.name === "AbortError";
    logger.error("Ritten partial request failed", {
      lead_id: partial.lead_id,
      error_code: aborted ? "RITTEN_TIMEOUT" : "RITTEN_NETWORK_ERROR",
    });
    throw new Error(aborted ? "RITTEN_TIMEOUT" : "RITTEN_NETWORK_ERROR");
  } finally {
    clearTimeout(timeout);
  }

  if (!response.ok) {
    logger.error("Ritten returned non-2xx for partial", {
      lead_id: partial.lead_id,
      error_code: `RITTEN_HTTP_${response.status}`,
    });
    throw new Error(`RITTEN_HTTP_${response.status}`);
  }

  logger.info("Partial sent to Ritten", { lead_id: partial.lead_id });
}
