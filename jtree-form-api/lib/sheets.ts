/**
 * Google Sheets fallback writer.
 * Appends a lead row to the configured spreadsheet.
 */

import { google } from "googleapis";
import type { Lead } from "./validate.js";
import { logger } from "./logger.js";

function getAuth() {
  const json = process.env.GOOGLE_SERVICE_ACCOUNT_JSON;
  if (!json) {
    throw new Error("GOOGLE_SERVICE_ACCOUNT_JSON not set");
  }

  const credentials = JSON.parse(json);
  return new google.auth.GoogleAuth({
    credentials,
    scopes: ["https://www.googleapis.com/auth/spreadsheets"],
  });
}

export async function appendLeadToSheet(lead: Lead): Promise<void> {
  const spreadsheetId = process.env.GOOGLE_SHEETS_ID;
  if (!spreadsheetId) {
    throw new Error("GOOGLE_SHEETS_ID not set");
  }

  const auth = getAuth();
  const sheets = google.sheets({ version: "v4", auth });

  const row = [
    lead.lead_id,
    lead.submitted_at,
    lead.parent_first_name,
    lead.parent_last_name,
    lead.parent_email,
    lead.parent_phone,
    String(lead.teen_age),
    lead.program_interest,
    lead.best_time_to_call,
    lead.how_did_you_hear ?? "",
  ];

  await sheets.spreadsheets.values.append({
    spreadsheetId,
    range: "Leads!A:J",
    valueInputOption: "RAW",
    requestBody: {
      values: [row],
    },
  });

  logger.info("Lead written to Google Sheets", { lead_id: lead.lead_id });
}

/**
 * Count leads submitted in the last N milliseconds.
 * Used by the watchdog cron to check if any leads came in.
 */
export async function countRecentLeads(windowMs: number): Promise<number> {
  const spreadsheetId = process.env.GOOGLE_SHEETS_ID;
  if (!spreadsheetId) {
    throw new Error("GOOGLE_SHEETS_ID not set");
  }

  const auth = getAuth();
  const sheets = google.sheets({ version: "v4", auth });

  const result = await sheets.spreadsheets.values.get({
    spreadsheetId,
    range: "Leads!B:B", // Column B = submitted_at timestamps
  });

  const rows = result.data.values ?? [];
  const cutoff = new Date(Date.now() - windowMs).toISOString();
  let count = 0;

  for (const row of rows) {
    const ts = row[0];
    if (typeof ts === "string" && ts >= cutoff) {
      count++;
    }
  }

  return count;
}
