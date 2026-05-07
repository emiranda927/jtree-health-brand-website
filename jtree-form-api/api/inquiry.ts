import type { VercelRequest, VercelResponse } from "@vercel/node";
import { nanoid } from "nanoid";
import { InquirySchema, type Lead } from "../lib/validate.js";
import { checkRateLimit } from "../lib/rateLimit.js";
import { sendToRitten } from "../lib/ritten.js";
import { appendLeadToSheet } from "../lib/sheets.js";
import { sendEmailToAdmissions } from "../lib/email.js";
import { verifyTurnstile } from "../lib/turnstile.js";
import { logger } from "../lib/logger.js";

const ALLOWED_ORIGIN = process.env.ALLOWED_ORIGIN || "https://jtreehealth.com";

function getCorsHeaders() {
  return {
    "Access-Control-Allow-Origin": ALLOWED_ORIGIN,
    "Access-Control-Allow-Methods": "POST, OPTIONS",
    "Access-Control-Allow-Headers": "Content-Type",
    "Access-Control-Max-Age": "86400",
  };
}

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
  const cors = getCorsHeaders();
  for (const [key, value] of Object.entries(cors)) {
    res.setHeader(key, value);
  }

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
    res.status(200).json({ success: true });
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

  // 5. Build lead
  const lead: Lead = {
    lead_id: `JT-${nanoid(10)}`,
    submitted_at: new Date().toISOString(),
    parent_first_name: data.parent_first_name,
    parent_last_name: data.parent_last_name,
    parent_email: data.parent_email,
    parent_phone: data.parent_phone,
    teen_age: data.teen_age,
    program_interest: data.program_interest,
    best_time_to_call: data.best_time_to_call,
    how_did_you_hear: data.how_did_you_hear,
    session_id: data.session_id,
  };

  logger.info("Lead received", { lead_id: lead.lead_id });

  // 6. Dual delivery
  const [crmResult, emailResult] = await Promise.allSettled([
    sendToRittenOrSheets(lead),
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

  res.status(200).json({
    success: true,
    message: "Thank you! Our admissions team will contact you shortly.",
  });
}

/**
 * Try Ritten first; if it fails (not configured or error), fall back to Google Sheets.
 */
async function sendToRittenOrSheets(lead: Lead): Promise<void> {
  try {
    await sendToRitten(lead);
  } catch {
    logger.info("Falling back to Google Sheets", { lead_id: lead.lead_id });
    await appendLeadToSheet(lead);
  }
}
