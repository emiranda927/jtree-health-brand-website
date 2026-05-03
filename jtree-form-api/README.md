# jtree-form-api

Vercel serverless API for Joshua Tree Health inquiry form submissions.

## Endpoints

| Endpoint | Purpose |
|---|---|
| `POST /api/inquiry` | Form submission handler — validates, rate limits, then dual-delivers to (Ritten or Sheets) **and** Resend email |
| `GET /api/health` | Health check for UptimeRobot |
| `GET /api/watchdog` | Daily cron (Mon–Fri 6 PM ET) — alerts if zero leads in 24h |

## Privacy

Never stores PHI. The form has no free-text fields. The logger never persists email, phone, or any field values — only `lead_id`, timestamps, and delivery channel statuses.

## Setup

1. `cp .env.example .env` and fill in values
2. `npm install`
3. `npm run dev` — Vercel dev server on `http://localhost:3000`
4. `npm test` — vitest suite (39 tests)

## Deployment (Vercel)

```bash
# First time:
vercel link        # connect to a Vercel project
vercel env add     # add each var from .env.example as Production env var
vercel --prod      # ship

# Subsequent deploys: push to GitHub main → Vercel auto-deploys
```

After first deploy, point `api.jtreehealth.com` at Vercel via Cloudflare DNS (CNAME → `cname.vercel-dns.com`), then add the custom domain in the Vercel project settings.

## Environment Variables

| Variable | Required | Description |
|---|---|---|
| `RITTEN_API_URL` | optional | Full POST endpoint for Ritten contacts (or Keragon/Zapier relay). Leave blank to skip Ritten. |
| `RITTEN_API_KEY` | optional | Bearer token sent as `Authorization: Bearer <key>`. |
| `RESEND_API_KEY` | **required** | Resend API key for admissions notifications. |
| `ADMISSIONS_EMAIL` | optional | Recipient (default `admissions@jtreehealth.com`). |
| `OWNER_ALERT_EMAIL` | required for watchdog | Recipient for "no leads in 24h" alerts. |
| `GOOGLE_SHEETS_ID` | **required** | Spreadsheet ID for the Sheets fallback. |
| `GOOGLE_SERVICE_ACCOUNT_JSON` | **required** | Service account JSON, stringified. Must have edit access to the sheet. |
| `ALLOWED_ORIGIN` | **required** | CORS origin — set to `https://jtreehealth.com` in production. |

## Ritten CRM

The `lib/ritten.ts` client posts a JSON payload as a "contact" to `RITTEN_API_URL` with `Authorization: Bearer <RITTEN_API_KEY>`. The body is built by `buildRittenPayload()`:

```json
{
  "source": "website_form",
  "external_id": "JT-...",
  "submitted_at": "2026-04-25T...Z",
  "contact": { "first_name": "...", "last_name": "...", "email": "...", "phone": "+1...", "role": "parent_guardian" },
  "patient": { "age": 14, "relationship_to_contact": "child" },
  "inquiry": { "program_interest": "IOP", "best_time_to_call": "Morning", "how_did_you_hear": "Google" }
}
```

**Behavior:**
- 5-second timeout — never stalls the user-facing form on a slow CRM.
- Non-2xx, network error, or timeout → fall through to Google Sheets so the lead is never lost.
- Both Ritten and Sheets failing → 500 to the user, alert via watchdog.

**Tuning the payload:** if Ritten's intake schema differs (different field names, required `case` block, etc.), edit `buildRittenPayload()` in `lib/ritten.ts`. The HTTP, auth, and error-handling plumbing stays unchanged. Re-run `npm test` to verify.

**Fallback path:** when `RITTEN_API_URL` or `RITTEN_API_KEY` is unset, the client throws `RITTEN_NOT_CONFIGURED` and the inquiry handler appends to Google Sheets instead. Use this until Ritten credentials are confirmed.

## Watchdog cron

`vercel.json` schedules `GET /api/watchdog` at `0 22 * * 1-5` (UTC = 6 PM ET, Mon–Fri). It counts rows in the Sheets `Leads` tab over the past 24h and emails `OWNER_ALERT_EMAIL` if zero — protecting against silent breakage of both delivery paths.
