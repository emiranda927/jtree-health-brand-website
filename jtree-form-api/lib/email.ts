/**
 * Resend transactional email sender.
 * Sends formatted lead notifications to admissions and alert emails.
 */

import { Resend } from "resend";
import type { Lead } from "./validate.js";
import { logger } from "./logger.js";

function getClient(): Resend {
  const key = process.env.RESEND_API_KEY;
  if (!key) {
    throw new Error("RESEND_API_KEY not set");
  }
  return new Resend(key);
}

/**
 * Send a formatted HTML email with the lead data to the admissions team.
 */
export async function sendEmailToAdmissions(lead: Lead): Promise<void> {
  const resend = getClient();
  const to = process.env.ADMISSIONS_EMAIL || "admissions@jtreehealth.com";

  const html = buildLeadEmailHtml(lead);

  const { error } = await resend.emails.send({
    from: "Joshua Tree Health <noreply@jtreehealth.com>",
    to: [to],
    subject: `New Inquiry: ${lead.parent_first_name} ${lead.parent_last_name} — ${lead.program_interest}`,
    html,
  });

  if (error) {
    logger.error("Resend email failed", {
      lead_id: lead.lead_id,
      error_code: error.name,
    });
    throw new Error(`Resend error: ${error.name} — ${error.message}`);
  }

  logger.info("Admissions email sent", { lead_id: lead.lead_id });
}

/**
 * Send a watchdog alert email when zero leads are received.
 */
export async function sendAlertEmail(subject: string, body: string): Promise<void> {
  const resend = getClient();
  const to = process.env.OWNER_ALERT_EMAIL;
  if (!to) {
    logger.warn("OWNER_ALERT_EMAIL not set — cannot send alert");
    return;
  }

  const { error } = await resend.emails.send({
    from: "Joshua Tree Health Alerts <noreply@jtreehealth.com>",
    to: [to],
    subject,
    html: `<div style="font-family:sans-serif;padding:20px;">${body}</div>`,
  });

  if (error) {
    logger.error("Alert email failed", { error_code: error.name });
    throw new Error(`Resend alert error: ${error.name}`);
  }

  logger.info("Alert email sent");
}

function buildLeadEmailHtml(lead: Lead): string {
  const rows = [
    ["Lead ID", lead.lead_id],
    ["Submitted", new Date(lead.submitted_at).toLocaleString("en-US", { timeZone: "America/Los_Angeles" })],
    ["Parent Name", `${lead.parent_first_name} ${lead.parent_last_name}`],
    ["Email", lead.parent_email],
    ["Phone", lead.parent_phone],
    ["Teen Age", String(lead.teen_age)],
    ["Program Interest", lead.program_interest],
    ["Best Time to Call", lead.best_time_to_call],
    ["How Did You Hear", lead.how_did_you_hear ?? "—"],
  ];

  const tableRows = rows
    .map(
      ([label, value]) =>
        `<tr>
          <td style="padding:8px 12px;border:1px solid #e2e8f0;font-weight:600;background:#f7fafc;white-space:nowrap;">${label}</td>
          <td style="padding:8px 12px;border:1px solid #e2e8f0;">${escapeHtml(value)}</td>
        </tr>`
    )
    .join("\n");

  return `
    <div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;max-width:600px;margin:0 auto;">
      <div style="background:#2d6a4f;color:white;padding:20px 24px;border-radius:8px 8px 0 0;">
        <h2 style="margin:0;font-size:20px;">New Inquiry Received</h2>
        <p style="margin:4px 0 0;opacity:0.9;font-size:14px;">Joshua Tree Health — Admissions</p>
      </div>
      <table style="width:100%;border-collapse:collapse;border:1px solid #e2e8f0;">
        ${tableRows}
      </table>
      <div style="padding:16px 24px;background:#f7fafc;border:1px solid #e2e8f0;border-top:0;border-radius:0 0 8px 8px;">
        <p style="margin:0;font-size:13px;color:#718096;">
          Please follow up within 1 business hour during office hours.
        </p>
      </div>
    </div>
  `;
}

function escapeHtml(str: string): string {
  return str
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;");
}
