import type { VercelRequest, VercelResponse } from "@vercel/node";
import { countRecentLeads } from "../lib/sheets.js";
import { sendAlertEmail } from "../lib/email.js";
import { logger } from "../lib/logger.js";

const TWENTY_FOUR_HOURS_MS = 24 * 60 * 60 * 1000;

export default async function handler(
  req: VercelRequest,
  res: VercelResponse
): Promise<void> {
  try {
    const count = await countRecentLeads(TWENTY_FOUR_HOURS_MS);

    logger.info("Watchdog check", {
      message: `Lead count in last 24h: ${count}`,
    });

    if (count === 0) {
      await sendAlertEmail(
        "\u26a0\ufe0f JTree: 0 inquiries received in last 24h \u2014 verify form is working",
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
           This alert is sent Mon\u2013Fri at 6:00 PM ET by the JTree watchdog cron.
         </p>`
      );
      logger.warn("Zero-lead alert sent");
    }

    res.status(200).json({ checked: true, count });
  } catch (err) {
    const message = err instanceof Error ? err.message : "Unknown error";
    logger.error("Watchdog failed", { error_code: message });
    res.status(500).json({ error: "Watchdog check failed" });
  }
}
