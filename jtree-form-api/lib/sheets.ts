/**
 * Google Sheets fallback writer.
 *
 * Writes inquiries to a single 12-column sheet:
 *   A: lead_id            G: teen_age            (blank for partials)
 *   B: submitted_at       H: program_interest
 *   C: parent_first_name  I: best_time_to_call
 *   D: parent_last_name   J: how_did_you_hear
 *   E: parent_email       K: status              ('lead' | 'partial')
 *   F: parent_phone       L: session_id
 *
 * IMPORTANT: the existing sheet must have columns K + L added by hand (header
 * row: `status`, `session_id`). Pre-existing rows will have those cells blank,
 * which the admissions team can read as "lead" for legacy data.
 */

import { google } from "googleapis";
import type { Lead, PartialLead } from "./validate.js";
import { logger } from "./logger.js";

const SHEET_RANGE = "Leads!A:L";

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

async function appendRow(row: string[]): Promise<void> {
  const spreadsheetId = process.env.GOOGLE_SHEETS_ID;
  if (!spreadsheetId) {
    throw new Error("GOOGLE_SHEETS_ID not set");
  }

  const auth = getAuth();
  const sheets = google.sheets({ version: "v4", auth });

  await sheets.spreadsheets.values.append({
    spreadsheetId,
    range: SHEET_RANGE,
    valueInputOption: "RAW",
    requestBody: { values: [row] },
  });
}

export async function appendLeadToSheet(lead: Lead): Promise<void> {
  // Legacy mirror: the sheet keeps its original 12-column layout. The form now
  // collects one name, so it goes in the former first-name column (C) and the
  // last-name column (D) is left blank.
  const row = [
    lead.lead_id,
    lead.submitted_at,
    lead.name,
    "",
    lead.email,
    lead.phone,
    String(lead.teen_age),
    lead.program_interest,
    lead.best_time_to_call ?? "",
    lead.how_did_you_hear ?? "",
    "lead",
    lead.session_id ?? "",
  ];

  await appendRow(row);
  logger.info("Lead written to Google Sheets", { lead_id: lead.lead_id });
}

/**
 * Append a no-PII partial inquiry. Columns C–F (name/email/phone) are
 * intentionally blank — partials never carry identity.
 */
export async function appendPartialToSheet(partial: PartialLead): Promise<void> {
  const row = [
    partial.lead_id,
    partial.submitted_at,
    "", // parent_first_name
    "", // parent_last_name
    "", // parent_email
    "", // parent_phone
    partial.teen_age != null ? String(partial.teen_age) : "",
    partial.program_interest ?? "",
    partial.best_time_to_call ?? "",
    partial.how_did_you_hear ?? "",
    "partial",
    partial.session_id,
  ];

  await appendRow(row);
  logger.info("Partial written to Google Sheets", { lead_id: partial.lead_id });
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

  // Pull timestamps + status so the watchdog only counts true leads, not
  // partials (which would otherwise inflate the count and silence alerts).
  const result = await sheets.spreadsheets.values.get({
    spreadsheetId,
    range: "Leads!B:K",
  });

  const rows = result.data.values ?? [];
  const cutoff = new Date(Date.now() - windowMs).toISOString();
  let count = 0;

  for (const row of rows) {
    const ts = row[0];
    const status = row[9]; // K column relative to B = index 9
    const isLead = status === "lead" || status === undefined || status === "";
    if (isLead && typeof ts === "string" && ts >= cutoff) {
      count++;
    }
  }

  return count;
}
