import type { VercelRequest, VercelResponse } from "@vercel/node";
import { nanoid } from "nanoid";
import { InquirySchema, type Lead } from "../lib/validate.js";
import { checkRateLimit } from "../lib/rateLimit.js";
import { sendToSupabase } from "../lib/supabase.js";
import { appendLeadToSheet } from "../lib/sheets.js";
import { sendEmailToAdmissions } from "../lib/email.js";
import { verifyTurnstile } from "../lib/turnstile.js";
import { applyCorsHeaders } from "../lib/cors.js";
import { logger } from "../lib/logger.js";

function getClientIp(req: VercelRequest): string {
  const forwarded = req.headers["x-forwarded-for"];
  if (typeof forwarded === "string") {
    return forwarded.split(",")[0]!.trim();
  }
  return req.socket?.remoteAddress ?? "unknown";
}

// Shared by the real success path AND the honeypot branch — the two responses
// must stay byte-identical so a bot can't tell a swallowed submission from a
// delivered one.
const SUCCESS_BODY = {
  success: true,
  message: "Thank you! Our admissions team will contact you shortly.",
};

export default async function handler(
  req: VercelRequest,
  res: VercelResponse
): Promise<void> {
  applyCorsHeaders(req, res);

  // Handle CORS preflight
  if (req.method === "OPTIONS") {
    res.status(204).end();
    return;
  }

  // 1. Method check
  if (req.method !== "POST") {
    res.status(405).json({ error: "Method not allowed" });
    return;
  }

  // 2. Rate limit
  const ip = getClientIp(req);
  const rateCheck = checkRateLimit(ip);
  if (!rateCheck.allowed) {
    logger.warn("Rate limit exceeded", { error_code: "RATE_LIMITED" });
    res.setHeader("Retry-After", String(Math.ceil((rateCheck.retryAfterMs ?? 60000) / 1000)));
    res.status(429).json({ error: "Too many requests. Please try again later." });
    return;
  }

  // 3. Parse and validate
  const parsed = InquirySchema.safeParse(req.body);
  if (!parsed.success) {
    const firstError = parsed.error.issues[0];
    res.status(422).json({
      error: "Validation failed",
      field: firstError?.path.join("."),
      message: firstError?.message,
    });
    return;
  }

  const data = parsed.data;

  // 4. Honeypot check — return 200 silently to fool bots
  if (data.hp_field && data.hp_field.length > 0) {
    logger.info("Honeypot triggered");
    res.status(200).json(SUCCESS_BODY);
    return;
  }

  // 4b. Cloudflare Turnstile verification. Falls open when secret unset
  // (dev/staging without a keypair) and gates submissions in production.
  const turnstileOk = await verifyTurnstile(data.cf_turnstile_response, ip);
  if (!turnstileOk) {
    res.status(403).json({
      error: "Verification failed",
      message: "Please complete the verification challenge and try again.",
    });
    return;
  }

  // 5. Build lead — carry every submitted field through (drop the honeypot and
  // the turnstile token, which are transport concerns, not lead data).
  const { hp_field: _hp, cf_turnstile_response: _turnstile, ...leadFields } = data;
  const lead: Lead = {
    lead_id: `JT-${nanoid(10)}`,
    submitted_at: new Date().toISOString(),
    ...leadFields,
  };

  logger.info("Lead received", { lead_id: lead.lead_id });

  // 6. Dual delivery
  const [crmResult, emailResult] = await Promise.allSettled([
    deliverToCrm(lead),
    sendEmailToAdmissions(lead),
  ]);

  const crmOk = crmResult.status === "fulfilled";
  const emailOk = emailResult.status === "fulfilled";

  // 9. Log delivery status — NEVER log the body
  logger.info("Delivery complete", {
    lead_id: lead.lead_id,
    delivery: {
      crm: crmOk ? "success" : "failed",
      email: emailOk ? "success" : "failed",
    },
  });

  // 7. Both failed
  if (!crmOk && !emailOk) {
    logger.error("Both delivery channels failed", {
      lead_id: lead.lead_id,
      error_code: "DUAL_DELIVERY_FAILURE",
    });
    res.status(500).json({
      error: "We could not process your inquiry. Please call us directly.",
    });
    return;
  }

  // 8. One failed — log warning, still return 200
  if (!crmOk || !emailOk) {
    const failedChannel = !crmOk ? "crm" : "email";
    logger.warn(`Partial delivery — ${failedChannel} channel failed`, {
      lead_id: lead.lead_id,
      delivery: {
        crm: crmOk ? "success" : "failed",
        email: emailOk ? "success" : "failed",
      },
    });
  }

  res.status(200).json(SUCCESS_BODY);
}

/**
 * Deliver the lead to the CRM. Supabase is the system of record — a successful
 * insert puts the lead straight onto the intake pipeline board. Google Sheets
 * is written in parallel as a best-effort mirror until it's retired.
 *
 * The channel counts as delivered if EITHER store accepts the lead, so a lead
 * is never lost to a single-store outage. When the Supabase env vars are unset,
 * `sendToSupabase` throws immediately and this falls back to Sheets-only —
 * i.e. safe to ship before the Supabase credentials are configured.
 */
async function deliverToCrm(lead: Lead): Promise<void> {
  const [supa, sheet] = await Promise.allSettled([
    sendToSupabase(lead),
    appendLeadToSheet(lead),
  ]);
  const supaOk = supa.status === "fulfilled";
  const sheetOk = sheet.status === "fulfilled";

  if (!supaOk && !sheetOk) {
    throw new Error("CRM_DELIVERY_FAILURE");
  }
  if (!supaOk) {
    logger.warn("Supabase insert failed — lead saved to Sheets only", { lead_id: lead.lead_id });
  } else if (!sheetOk) {
    logger.warn("Sheets mirror failed — lead saved to Supabase", { lead_id: lead.lead_id });
  }
}
