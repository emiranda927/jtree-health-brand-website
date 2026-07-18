/**
 * POST /api/careers/apply
 *
 * Career applications. Lighter than the clinical inquiry funnel — no CRM,
 * no spreadsheet, just a Resend email to the careers inbox. Reply-to is set
 * to the applicant's email so the team can answer with one click.
 *
 * Same Turnstile gate as inquiries — careers forms attract bot traffic
 * looking for free email blasts.
 */
import type { VercelRequest, VercelResponse } from "@vercel/node";
import { nanoid } from "nanoid";
import { CareerApplicationSchema, type CareerApplication } from "../../lib/validate.js";
import { checkRateLimit } from "../../lib/rateLimit.js";
import { sendCareerApplicationEmail } from "../../lib/email.js";
import { verifyTurnstile } from "../../lib/turnstile.js";
import { applyCorsHeaders } from "../../lib/cors.js";
import { logger } from "../../lib/logger.js";

function getClientIp(req: VercelRequest): string {
  const forwarded = req.headers["x-forwarded-for"];
  if (typeof forwarded === "string") return forwarded.split(",")[0]!.trim();
  return req.socket?.remoteAddress ?? "unknown";
}

// Shared by the real success path AND the honeypot branch — the two responses
// must stay byte-identical so a bot can't tell a swallowed submission from a
// delivered one.
const SUCCESS_BODY = {
  success: true,
  message: "Thanks — we'll be in touch.",
};

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

  const ip = getClientIp(req);
  const rateCheck = checkRateLimit(ip);
  if (!rateCheck.allowed) {
    logger.warn("Career rate limit exceeded", { error_code: "RATE_LIMITED" });
    res.setHeader(
      "Retry-After",
      String(Math.ceil((rateCheck.retryAfterMs ?? 60000) / 1000))
    );
    res.status(429).json({ error: "Too many submissions. Please try again later." });
    return;
  }

  const parsed = CareerApplicationSchema.safeParse(req.body);
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

  if (data.hp_field && data.hp_field.length > 0) {
    logger.info("Career honeypot triggered");
    res.status(200).json(SUCCESS_BODY);
    return;
  }

  const turnstileOk = await verifyTurnstile(data.cf_turnstile_response, ip);
  if (!turnstileOk) {
    res.status(403).json({
      error: "Verification failed",
      message: "Please complete the verification challenge and try again.",
    });
    return;
  }

  const application: CareerApplication = {
    application_id: `JT-C-${nanoid(10)}`,
    submitted_at: new Date().toISOString(),
    applicant_first_name: data.applicant_first_name,
    applicant_last_name: data.applicant_last_name,
    applicant_email: data.applicant_email,
    applicant_phone: data.applicant_phone,
    role_interest: data.role_interest,
    message: data.message,
    resume_url: data.resume_url,
  };

  logger.info("Career application received", { lead_id: application.application_id });

  try {
    await sendCareerApplicationEmail(application);
  } catch {
    res.status(500).json({
      error: "We couldn't send your application right now. Please email careers@jtreehealth.com directly.",
    });
    return;
  }

  res.status(200).json(SUCCESS_BODY);
}
