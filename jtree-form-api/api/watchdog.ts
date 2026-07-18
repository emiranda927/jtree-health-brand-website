import type { VercelRequest, VercelResponse } from "@vercel/node";
import { countRecentLeads } from "../lib/sheets.js";
import { sendAlertEmail, escapeHtml } from "../lib/email.js";
import { logger } from "../lib/logger.js";

const TWENTY_FOUR_HOURS_MS = 24 * 60 * 60 * 1000;

export default async function handler(
  req: VercelRequest,
  res: VercelResponse
): Promise<void> {
  let count: number;
  try {
    count = await countRecentLeads(TWENTY_FOUR_HOURS_MS);
  } catch (err) {
    const message = err instanceof Error ? err.message : "Unknown error";
    logger.error("Watchdog could not read the leads sheet", {
      error_code: message,
    });
    // An unreadable sheet means lead volume is unmonitored — that is itself
    // an alertable incident, not just a failed cron run.
    try {
      await sendAlertEmail(
        "⚠️ JTree watchdog cannot read the leads sheet",
        `<h3>Watchdog Error</h3>
         <p>The watchdog cron could not read the Google Sheet, so lead volume
         is currently unmonitored.</p>
         <p>Error: <code>${escapeHtml(message)}</code></p>
         <p>Check the Google Sheets credentials
         (<code>GOOGLE_SERVICE_ACCOUNT_JSON</code>, <code>GOOGLE_SHEETS_ID</code>)
         and the sheet's sharing settings.</p>`
      );
    } catch (alertErr) {
      const alertMessage =
        alertErr instanceof Error ? alertErr.message : "Unknown error";
      logger.error("Watchdog failure alert could not be sent", {
        error_code: alertMessage,
      });
    }
    res.status(500).json({ error: "Watchdog check failed" });
    return;
  }

  logger.info(`Watchdog check — lead count in last 24h: ${count}`);

  if (count > 0) {
    res.status(200).json({ checked: true, count, alerted: false });
    return;
  }

  try {
    await sendAlertEmail(
      "⚠️ JTree: 0 inquiries received in last 24h — verify form is working",
      `<h3>Watchdog Alert</h3>
       <p>No inquiries have been received in the last 24 hours.</p>
       <p>Please verify:</p>
       <ul>
         <li>The website form is rendering correctly</li>
         <li>The API endpoint is responding (check <code>/api/health</code>)</li>
         <li>Google Sheets credentials are valid</li>
         <li>Resend API key is active</li>
       </ul>
       <p style="color:#718096;font-size:13px;">
         This alert is sent Mon–Fri at 6:00 PM ET by the JTree watchdog cron.
       </p>`
    );
  } catch (err) {
    const message = err instanceof Error ? err.message : "Unknown error";
    logger.error("Zero-lead alert could not be sent", { error_code: message });
    res.status(500).json({ error: "Watchdog alert failed" });
    return;
  }

  logger.warn("Zero-lead alert sent");
  res.status(200).json({ checked: true, count, alerted: true });
}
