/**
 * POST /api/inquiry/partial
 *
 * No-PII partial inquiry. Fired by form.js on pagehide when the parent
 * interacted with the form but did not submit. Persists the interest signal
 * (teen_age, program_interest, best_time_to_call, how_did_you_hear) plus
 * acquisition source (UTMs, referrer) and a per-tab session_id so admissions
 * can correlate to a future full submission.
 *
 * Response is always 204. Browsers treat sendBeacon as fire-and-forget; we
 * never want a stalled response to delay the page unload.
 */
import type { VercelRequest, VercelResponse } from "@vercel/node";
import { nanoid } from "nanoid";
import { PartialInquirySchema, type PartialLead } from "../../lib/validate.js";
import { checkRateLimit } from "../../lib/rateLimit.js";
import { sendPartialToSupabase } from "../../lib/supabase.js";
import { appendPartialToSheet } from "../../lib/sheets.js";
import { applyCorsHeaders } from "../../lib/cors.js";
import { logger } from "../../lib/logger.js";

function getClientIp(req: VercelRequest): string {
  const forwarded = req.headers["x-forwarded-for"];
  if (typeof forwarded === "string") {
    return forwarded.split(",")[0]!.trim();
  }
  return req.socket?.remoteAddress ?? "unknown";
}

export default async function handler(
  req: VercelRequest,
  res: VercelResponse
): Promise<void> {
  applyCorsHeaders(req, res);

  if (req.method === "OPTIONS") {
    res.status(204).end();
    return;
  }

  if (req.method !== "POST") {
    res.status(405).json({ error: "Method not allowed" });
    return;
  }

  // sendBeacon submits as text/plain or application/json depending on the
  // browser. Parse manually so both shapes are accepted.
  let body: unknown = req.body;
  if (typeof body === "string") {
    try { body = JSON.parse(body); } catch { body = null; }
  }

  // Rate-limit by IP, same store as the main endpoint. Partial flood from a
  // single IP is the most likely abuse vector.
  const ip = getClientIp(req);
  const rateCheck = checkRateLimit(ip);
  if (!rateCheck.allowed) {
    logger.warn("Partial rate limit exceeded", { error_code: "RATE_LIMITED" });
    res.status(204).end();
    return;
  }

  const parsed = PartialInquirySchema.safeParse(body);
  if (!parsed.success) {
    // Stay quiet — no body for the browser to act on, this is a beacon.
    logger.warn("Partial validation failed", {
      error_code: `PARTIAL_INVALID_${parsed.error.issues[0]?.path.join(".") || "unknown"}`,
    });
    res.status(204).end();
    return;
  }

  const data = parsed.data;

  // Honeypot — silent success
  if (data.hp_field && data.hp_field.length > 0) {
    logger.info("Partial honeypot triggered");
    res.status(204).end();
    return;
  }

  const { hp_field: _hp, ...partialFields } = data;
  const partial: PartialLead = {
    lead_id: `JT-P-${nanoid(10)}`,
    submitted_at: new Date().toISOString(),
    ...partialFields,
  };

  logger.info("Partial received", { lead_id: partial.lead_id });

  // Deliver to Supabase (the CRM's abandoned-inquiry store) with Sheets as a
  // best-effort mirror. We never block — the browser has already moved on.
  const [supa, sheet] = await Promise.allSettled([
    sendPartialToSupabase(partial),
    appendPartialToSheet(partial),
  ]);

  logger.info("Partial delivery complete", {
    lead_id: partial.lead_id,
    delivery: {
      supabase: supa.status === "fulfilled" ? "success" : "failed",
      sheets: sheet.status === "fulfilled" ? "success" : "failed",
    },
  });

  res.status(204).end();
}
