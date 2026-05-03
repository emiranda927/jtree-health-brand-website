# JTree Health — End-to-End Launch Guide

Two repos ship together:

| Repo | Hosts | Domain |
|---|---|---|
| `jtree-form-api/` | Vercel (serverless) | `api.jtreehealth.com` |
| `jtree-wp-theme/` | WP Engine (WordPress) | `jtreehealth.com`, `www.jtreehealth.com` |

DNS sits at Cloudflare. Analytics is GA4 via GTM.

The form API is a separate origin so the WordPress host never touches lead data. Submissions go straight from the browser to `api.jtreehealth.com`, which fans out to (Ritten **or** Sheets) **and** Resend email.

---

## Order of operations

Follow this top to bottom — each step depends on the prior one.

### 1. Push both repos to GitHub

```bash
# Form API
cd jtree-form-api
gh repo create jtree-health/jtree-form-api --private --source=. --push

# WordPress theme
cd ../jtree-wp-theme
gh repo create jtree-health/jtree-wp-theme --private --source=. --push
```

(Replace `jtree-health/` with the actual GitHub org/user.) Both repos are already `git init`-ed locally with `.gitignore` in place; they have no commits yet — make the first commit before pushing.

### 2. Stand up the form API on Vercel

1. **Create Vercel project** — import `jtree-form-api` from GitHub.
2. **Set environment variables** (Production scope):
   - `RESEND_API_KEY` — from https://resend.com/api-keys
   - `ADMISSIONS_EMAIL` — `admissions@jtreehealth.com`
   - `OWNER_ALERT_EMAIL` — owner's monitored inbox
   - `GOOGLE_SHEETS_ID` — ID of the Leads spreadsheet
   - `GOOGLE_SERVICE_ACCOUNT_JSON` — entire service-account JSON, stringified, with editor access to the sheet
   - `ALLOWED_ORIGIN` — `https://jtreehealth.com`
   - `RITTEN_API_URL` + `RITTEN_API_KEY` — leave **blank** until Ritten credentials are confirmed (see §6)
3. **Deploy** — `vercel --prod` or push to `main`.
4. **Verify** — `curl https://<vercel-url>/api/health` → `200 OK`.
5. **Add custom domain** — Vercel project → Domains → add `api.jtreehealth.com`. Vercel will show the required CNAME — keep that tab open for §3.

### 3. Cloudflare DNS

In the Cloudflare zone for `jtreehealth.com`:

| Type | Name | Value | Proxy |
|---|---|---|---|
| A | `@` | WP Engine site IP | Proxied |
| CNAME | `www` | `<env>.wpengine.com` (per WP Engine instructions) | Proxied |
| CNAME | `api` | `cname.vercel-dns.com` | **DNS only** (Vercel handles its own TLS) |

Then under **SSL/TLS**:
- Mode: **Full (strict)**
- "Always Use HTTPS": **on**
- "Automatic HTTPS Rewrites": **on**
- HSTS: enable, max-age 6 months, include subdomains, preload

Under **Security → WAF**:
- Enable the Cloudflare-managed ruleset
- Add a custom rule blocking `xmlrpc.php` requests
- Bot Fight Mode: **on**

### 4. WP Engine

1. **Provision** a new site (Production environment).
2. **Add domain** in the User Portal: `jtreehealth.com` + `www.jtreehealth.com`.
3. **Issue Let's Encrypt SSL** for both.
4. **Install** the GeneratePress parent theme on the WP install.
5. **Upload** `jtree-wp-theme/` to `wp-content/themes/jtree-wp-theme/` — see `jtree-wp-theme/DEPLOY.md` for SFTP and GitHub action options.
6. **Activate** "JTree Health" in Appearance → Themes.
7. **Create pages and assign templates** — see the table in `jtree-wp-theme/DEPLOY.md`.
8. **Set Home as static front page** under Settings → Reading.

### 5. Analytics

Per `jtree-wp-theme/docs/analytics-config.md`:

1. Create GA4 property: privacy settings ON (IP anonymization ON, Google Signals OFF, Ads Personalization OFF, 14-month retention).
2. Create GTM container, install on every page (already wired in `inc/seo.php`).
3. Replace placeholder GTM/GA4 IDs in `inc/seo.php` with the real ones.
4. Confirm `inquiry_submitted` fires only on `/thank-you/`.
5. Install a GDPR/CCPA cookie banner that defaults to deny non-essential.

### 6. Ritten CRM (when ready)

The integration is implemented and tested but disabled until credentials land:

1. Get the contact-creation endpoint URL and an API token from Ritten.
2. Set `RITTEN_API_URL` and `RITTEN_API_KEY` in Vercel Production env.
3. Submit a real inquiry — confirm the contact appears in Ritten.
4. If Ritten's intake schema differs from the default payload (`buildRittenPayload` in `lib/ritten.ts`), adjust field names there and redeploy. The 5-second timeout, fallback-to-Sheets behavior, and Bearer auth need no changes.

If Ritten exposes its API only via Keragon/Zapier/Make, point `RITTEN_API_URL` at the relay's webhook URL and pass the relay's auth token as `RITTEN_API_KEY` — the same code path works.

### 7. Final smoke test

- [ ] `curl https://api.jtreehealth.com/api/health` → 200
- [ ] Submit `/admissions/` form on production with a real email + phone
- [ ] Inquiry appears in Ritten **or** Sheets (the configured path)
- [ ] Resend email lands in `ADMISSIONS_EMAIL` inbox
- [ ] Browser ends on `/thank-you/`, GA4 real-time shows `inquiry_submitted`
- [ ] securityheaders.com → A grade or better
- [ ] PageSpeed mobile ≥ 90 on home + admissions

### 8. Monitoring

- **UptimeRobot** keyword monitor on `https://api.jtreehealth.com/api/health` (1-min)
- **Vercel cron** — `vercel.json` already schedules `/api/watchdog` Mon–Fri 6 PM ET. Will email `OWNER_ALERT_EMAIL` if zero leads in 24h.
- **GA4** real-time, first 7 days

---

## Pre-launch open items

These need owner / client / vendor input before launch — not engineering:

- [ ] Real photos to replace placeholder treatments
- [ ] Final logo SVG from freelancer
- [ ] `og-image.jpg` 1200×630 (Open Graph thumbnail)
- [ ] Confirm `jtreehealth.com` is the domain (placeholder used throughout)
- [ ] Street address for Schema.org `LocalBusiness`
- [ ] Ritten API endpoint + token
- [ ] Resend domain verification (`noreply@jtreehealth.com`)
- [ ] Google service account + Sheets share
- [ ] GA4 property + GTM container IDs

## Known limitations

- 7 inner-page templates (`page-for-parents.php`, `page-for-teens.php`, `page-what-we-treat.php`, `page-insurance.php`, `page-contact.php`, `page-crisis.php`, `page-privacy.php`) are stubs. Designs/copy are pending — they should not be linked in primary nav at launch unless content is ready, or they should be filled in before go-live.
- Rate limiter is in-memory per Vercel function instance. For higher scale, swap to Upstash Redis (env vars are reserved in `.env.example`).
