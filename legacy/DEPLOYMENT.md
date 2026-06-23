# JTree Health — End-to-End Launch Guide

Two repos ship together:

| Repo | Hosts | Domain |
|---|---|---|
| `jtree-form-api/` | Vercel (serverless) | `api.jtreehealth.com` |
| `jtree-wp-theme/` | Flywheel (WordPress, Tiny plan) | `jtreehealth.com`, `www.jtreehealth.com` |

DNS sits at Cloudflare. Analytics is GA4 via GTM.

The form API is a separate origin so the WordPress host never touches lead data. Submissions go straight from the browser to `api.jtreehealth.com`, which fans out to (Ritten **or** Sheets) **and** Resend email.

**Partial-inquiry capture:** when a parent interacts with the form on `/admissions/` but doesn't submit, `form.js` sends a no-PII beacon to `POST /api/inquiry/partial` on page-leave (`pagehide` / `visibilitychange`). The partial carries `session_id`, `teen_age`, `program_interest`, `best_time_to_call`, `how_did_you_hear`, UTM params, and `referrer` — never name/email/phone. It lands in the same Ritten endpoint (with `stage: "partial"`) or the same Sheet (with `status: "partial"`, PII columns blank). Full submissions also carry `session_id` so admissions can correlate a partial → completed lead.

---

## Current readiness (2026-05-05)

| Layer | Status | Notes |
|---|---|---|
| Form API codebase | ✅ Done | Unmodified from upstream. 39 vitest specs passing. |
| Brand + design system | ✅ Done | Local-served Sora/Fraunces/Inter/JetBrains Mono/Caveat. No Google Fonts. |
| WP theme — home, programs, about, admissions, thank-you, for-teens | ✅ Done | Ported from the design system UI kit; partials drive header/footer/crisis bar. |
| WP theme — for-parents, what-we-treat, insurance, contact, crisis, privacy | ✅ Done | Functional content using design-system tokens. Copy is launch-ready placeholder; founder can refine. |
| Inquiry form rendering | ✅ Done | `jtree_render_inquiry_form()` outputs `.jth-input` markup matching the API contract. |
| Form submission wiring | ✅ Done | `assets/js/form.js` POSTs to `api.jtreehealth.com/api/inquiry`, redirects to `/thank-you/`. URL configurable via `window.JTREE_CONFIG`. |
| GA4 conversion event | ✅ Done | `inquiry_submitted` fires at `wp_head` priority 1, only on the thank-you page template. |
| Schema.org + OG + canonical | ✅ Done | `MedicalBusiness` JSON-LD, OG, Twitter card, canonical, theme-color, favicon set, content-language. |
| Sitemap | ✅ Done | WP core sitemap at `/wp-sitemap.xml`. `/thank-you/` excluded via `wp_sitemaps_posts_query_args`. |
| robots.txt | ✅ Done | Augmented dynamic robots via `robots_txt` filter. Disallows `/thank-you/`, `/wp-admin/`. |
| Security headers | ⚠️ Manual | Snippet at `jtree-wp-theme/htaccess-security.txt` — paste into the WP root `.htaccess` outside the WordPress-managed block. |
| Cookie banner | ⚠️ Manual | Pick CookieYes / Complianz / Cookiebot, install plugin, configure to default-deny non-essential. |
| Real domain | ⚠️ Pending | `jtreehealth.com` is the placeholder throughout — confirm before launch. |
| Real photography | ⚠️ Pending | Brand collage covers hero treatments; teen lifestyle photos optional. |
| `og-image.jpg` | ⚠️ Pending | See `assets/brand/og-image.README.txt`. 1200×630 jpg. |
| Street address | ⚠️ Pending | Footer + Schema.org `LocalBusiness`. |
| Ritten API endpoint + token | ⚠️ Pending | Vercel env vars `RITTEN_API_URL` + `RITTEN_API_KEY`. Falls back to Sheets until set. |
| Resend domain verification | ⚠️ Pending | DKIM/SPF in Cloudflare for `noreply@jtreehealth.com`. |
| Google service-account JSON | ⚠️ Pending | Vercel env var `GOOGLE_SERVICE_ACCOUNT_JSON` + sheet share. |
| GA4 + GTM IDs | ⚠️ Pending | Replace placeholders in `inc/seo.php` (or follow the cookie-banner plugin's GTM injection path). |

---

## Order of operations

Follow this top to bottom — each step depends on the prior one.

### 1. Push both repos to GitHub

```bash
cd jtree-form-api
gh repo create jtree-health/jtree-form-api --private --source=. --push

cd ../jtree-wp-theme
gh repo create jtree-health/jtree-wp-theme --private --source=. --push
```

(Replace `jtree-health/` with the actual GitHub org/user.)

### 2. Stand up the form API on Vercel

1. **Create Vercel project** — import `jtree-form-api` from GitHub.
2. **Set environment variables** (Production scope):
   - `RESEND_API_KEY` — from https://resend.com/api-keys
   - `ADMISSIONS_EMAIL` — `admissions@jtreehealth.com`
   - `OWNER_ALERT_EMAIL` — owner's monitored inbox
   - `GOOGLE_SHEETS_ID` — ID of the Leads spreadsheet
   - `GOOGLE_SERVICE_ACCOUNT_JSON` — entire service-account JSON, stringified
   - `ALLOWED_ORIGIN` — `https://jtreehealth.com`
   - `RITTEN_API_URL` + `RITTEN_API_KEY` — leave **blank** until Ritten credentials are confirmed
   - `TURNSTILE_SECRET` — Cloudflare Turnstile secret key. Leave **blank** to fall open in dev/staging; the verifier returns true and the form behaves as it does today (honeypot only). When set, every full inquiry is verified server-side.
   - In WordPress, expose the matching public site key via either the `JTREE_TURNSTILE_SITE_KEY` PHP constant in `wp-config.php` or the `jtree_turnstile_site_key` option (`update_option`). The widget renders only when this is set.
3. **Deploy** — `vercel --prod` or push to `main`.
4. **Verify** — `curl https://<vercel-url>/api/health` → `200 OK`.
5. **Add custom domain** — Vercel project → Domains → add `api.jtreehealth.com`.

### 3. Cloudflare DNS

In the Cloudflare zone for `jtreehealth.com`:

| Type | Name | Value | Proxy |
|---|---|---|---|
| A | `@` | Flywheel site IP (shown in the Flywheel dashboard → site → Domains → "Add a domain") | Proxied |
| CNAME | `www` | `<sitename>.flywheelsites.com` (per Flywheel's "Add a domain" instructions) | Proxied |
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

### 4. Flywheel

Eliseo is on the **Tiny plan** ($15/mo, 1 site, 5k visits, 5 GB storage). Steps:

1. **Provision** a new site in the Flywheel dashboard. Pick a temporary site name — the working URL will be `<sitename>.flywheelsites.com` until DNS cuts over.
2. **Add domain** in the Flywheel dashboard → site → Domains: `jtreehealth.com` + `www.jtreehealth.com`. Flywheel will show the A-record IP and CNAME target to use in Cloudflare.
3. **Issue Let's Encrypt SSL** for both (one-click from the Domains tab once DNS resolves to Flywheel).
4. **Install** the GeneratePress parent theme on the WP install (Appearance → Themes → Add New).
5. **Upload** `jtree-wp-theme/` to `wp-content/themes/jtree-wp-theme/`. Two paths:
   - **Easiest:** open the local site in the Local desktop app → click "Connect to Flywheel" → push. This syncs theme + database in one shot.
   - **Manual:** SFTP using the credentials in the Flywheel dashboard → site → Advanced → SFTP. Upload to `wp-content/themes/jtree-wp-theme/`, then activate from the WP admin.
6. **Append the security headers snippet** from `jtree-wp-theme/htaccess-security.txt` to the WP root `.htaccess` (edit via SFTP). Paste *outside* the `# BEGIN WordPress` / `# END WordPress` managed block — WordPress overwrites that block on every save.
7. **Create pages and assign templates** — see the table in `jtree-wp-theme/DEPLOY.md`. Each page needs slug + template:

   | Slug | Template |
   |---|---|
   | `/` (front page) | Home |
   | `programs` | Programs |
   | `about` | About |
   | `admissions` | Admissions |
   | `thank-you` | Thank You |
   | `for-parents` | For Parents |
   | `for-teens` | For Teens |
   | `what-we-treat` | What We Treat |
   | `insurance` | Insurance |
   | `contact` | Contact |
   | `crisis` | Crisis Resources |
   | `privacy` | Privacy Policy |

8. **Set the Home page as a static front page** under Settings → Reading.

### 5. Replace the og-image placeholder

Add `og-image.jpg` (1200×630) to `wp-content/themes/jtree-wp-theme/assets/brand/`. Until then, social-share previews will 404.

### 6. Analytics

Per `jtree-wp-theme/docs/analytics-config.md`:

1. Create GA4 property with privacy settings (IP anonymization ON, Google Signals OFF, Ads Personalization OFF, 14-month retention).
2. Create GTM container; install on every page (already wired in `inc/seo.php` if you replace the GTM placeholders, or via your cookie banner plugin).
3. Confirm `inquiry_submitted` fires only on `/thank-you/` (already implemented in `functions.php`).
4. Install a GDPR/CCPA cookie banner that defaults to deny non-essential.

### 7. Ritten CRM (when ready)

The integration is implemented and tested but disabled until credentials land:

1. Get the contact-creation endpoint URL and an API token from Ritten.
2. Set `RITTEN_API_URL` and `RITTEN_API_KEY` in Vercel Production env.
3. Submit a real inquiry — confirm the contact appears in Ritten.
4. If Ritten's intake schema differs from the default payload (`buildRittenPayload` in `lib/ritten.ts`), adjust field names there and redeploy.

### 7b. Update the Leads spreadsheet header row

Partial-inquiry capture extended the Sheets writer from columns A–J to A–L. Before the next deploy, open the Leads spreadsheet and add two new header cells:

| K | L |
|---|---|
| `status` | `session_id` |

Pre-existing rows will leave columns K and L blank; treat blank as `lead` for legacy data. Going forward, every row will be tagged `lead` or `partial` and carry a session id when one is available.

### 8. Final smoke test

- [ ] `curl https://api.jtreehealth.com/api/health` → 200
- [ ] Submit `/admissions/` form on production with a real email + phone
- [ ] Inquiry appears in Ritten **or** Sheets (the configured path) tagged `lead`
- [ ] Resend email lands in `ADMISSIONS_EMAIL` inbox
- [ ] Open `/admissions/`, change one of the select fields, then close the tab without submitting → a row tagged `partial` with no PII appears in Sheets/Ritten within ~10s
- [ ] Browser ends on `/thank-you/`, GA4 real-time shows `inquiry_submitted`
- [ ] securityheaders.com → A grade or better
- [ ] PageSpeed mobile ≥ 90 on home + admissions
- [ ] All 12 pages return 200 and render header/footer/crisis bar
- [ ] `/wp-sitemap.xml` lists all pages except `/thank-you/`
- [ ] `/robots.txt` disallows `/thank-you/` and `/wp-admin/`
- [ ] Click each Joshie pose / collage element on home — no broken images

### 9. Monitoring

- **UptimeRobot** keyword monitor on `https://api.jtreehealth.com/api/health` (1-min)
- **Vercel cron** — `vercel.json` already schedules `/api/watchdog` Mon–Fri 6 PM ET
- **GA4** real-time, first 7 days

---

## Local verification (before pushing to production)

### Form API tests

```bash
cd jtree-form-api
npm install
npm test          # 53 vitest specs (39 inquiry + ritten, 14 partial)
npm run dev       # vercel dev on :3000
```

Code in `jtree-form-api/` was not modified during this front-end pass — the upstream test suite should remain green.

### WP theme local check

The WP theme at `jtree-wp-theme/` is the single source of truth. To run it locally use Local by Flywheel, LocalWP, DDEV, or wp-env:
1. Spin up a fresh WP site
2. Install GeneratePress (parent)
3. Symlink or copy `jtree-wp-theme/` to `wp-content/themes/jtree-wp-theme/`
4. Activate the theme; create the 12 pages with their templates per the table above
5. Visit each page and the form on `/admissions/`

---

## Outstanding integration work

This is the single source of truth for everything that still has to happen before launch (and the few items that can wait until after). Grouped by *who acts*. When a row is done, check the box and date it in the right column.

### Vendor accounts to set up or finish setting up

| # | Item | Why it matters | Status |
|---|---|---|---|
| V1 | **Cloudflare Turnstile** — create site, capture **site key** (public) and **secret key** (private) at `dash.cloudflare.com` → Turnstile | Without this the form has only a honeypot. Falls open in code, so safe to defer, but should land before high-traffic launch. | ☐ |
| V2 | **Cloudflare DNS** — A record for `@`, CNAME for `www` (proxied), CNAME for `api` (DNS-only). SSL "Full (strict)", HSTS on, WAF rule blocking `xmlrpc.php`, Bot Fight Mode on. | DNS is the front door for both WP and the API. Misconfigured DNS = no traffic. | ☐ |
| V3 | **Flywheel (Tiny plan, already paid)** — create site in Flywheel dashboard, add `jtreehealth.com` + `www`, issue Let's Encrypt SSL, install GeneratePress parent, push `jtree-wp-theme/` (Local "Connect to Flywheel" or SFTP), append `htaccess-security.txt` outside the WP-managed block. | Production WP host. | ☐ |
| V4 | **Vercel** — import `jtree-form-api` repo, attach `api.jtreehealth.com` custom domain. | Production API host. | ☐ |
| V5 | **Resend** — verify the sending domain (DKIM, SPF, return-path records in Cloudflare DNS) for `noreply@jtreehealth.com`. | Without DKIM/SPF, lead-confirmation and admissions notification emails go to spam. | ☐ |
| V6 | **Google service account** — create in GCP, share the Leads spreadsheet with the service-account email as Editor, paste the JSON into Vercel env. | The form API writes to Sheets through this account. No share = "permission denied" on every submission. | ☐ |
| V7 | **Ritten** (or webhook relay — Keragon, Zapier, Make) — get the contact-creation endpoint URL and an API token. **New consideration:** the receiving config must tolerate a `stage: "partial"` payload that has no `contact` block — only a `session_id`, inquiry signals, and acquisition data. If it errors on those, the API falls back to Sheets, which is fine but loses the CRM trail. | Real CRM beats Sheets long-term. | ☐ |
| V8 | **GA4 property** — IP anonymization on, Google Signals **off**, Ads Personalization **off**, 14-month retention. | Privacy-respectful analytics baseline. | ☐ |
| V9 | **GTM container** — install on every page, replace placeholders in `inc/seo.php` (or via the cookie banner plugin's GTM injection path). | Tag management. | ☐ |
| V10 | **Cookie banner** — pick CookieYes / Complianz / Cookiebot, install plugin, configure to default-deny non-essential cookies. | GDPR/CCPA + enables conditional GTM firing. | ☐ |
| V11 | **UptimeRobot** — keyword monitor on `https://api.jtreehealth.com/api/health` (1-min interval). | First line of defense against silent API outages. | ☐ |

### Vercel environment variables (Production scope)

Set under Vercel → Project → Settings → Environment Variables. Each one only takes effect on the next deploy.

| Variable | Purpose | Status |
|---|---|---|
| `RESEND_API_KEY` | Auth for Resend transactional email | ☐ |
| `ADMISSIONS_EMAIL` | Where admissions notifications land (e.g., `admissions@jtreehealth.com`) | ☐ |
| `OWNER_ALERT_EMAIL` | Where the watchdog cron alerts go | ☐ |
| `CAREERS_EMAIL` | Where career-application emails land. **Optional** — falls back to `ADMISSIONS_EMAIL` if unset. | ☐ |
| `GOOGLE_SHEETS_ID` | Spreadsheet ID for the Leads sheet | ☐ |
| `GOOGLE_SERVICE_ACCOUNT_JSON` | Stringified service-account JSON (from V6) | ☐ |
| `ALLOWED_ORIGIN` | Allowed CORS origin for the form (e.g., `https://jtreehealth.com`) | ☐ |
| `RITTEN_API_URL` + `RITTEN_API_KEY` | CRM endpoint + bearer token. Leave blank until V7 lands; falls back to Sheets. | ☐ |
| `TURNSTILE_SECRET` | Cloudflare Turnstile secret key (private). Form-API verifier is gated by this — when blank, the verifier falls open and the form behaves as it did pre-Turnstile. | ☐ |

### WordPress configuration

| # | Item | How | Status |
|---|---|---|---|
| W1 | **Turnstile site key in WP** — exposes the public key to `form.js` / `careers.js` so the widget can render. | Either define `JTREE_TURNSTILE_SITE_KEY` in `wp-config.php`, or set the WP option: `update_option('jtree_turnstile_site_key', '0x4AAAAAA...')`. | ☐ |
| W2 | **Create the 12 core pages** with the right templates (table in §4 above). | WP admin → Pages → Add New, set Page Attributes → Template. Set Home as static front page in Settings → Reading. | ☐ |
| W3 | **Create the Careers page** — title "Careers", slug `careers`, template **Careers**, status **Draft**. The page renders `[DRAFT]` markers and a fictitious openings list — keep it as Draft until founder rewrites those. | WP admin. | ☐ |
| W4 | **Create the 4 Parent Guide child pages** under "For Parents" — set parent page to "For Parents", template **Parent Guide**, slugs: `is-this-a-crisis`, `php-vs-iop`, `insurance-and-cost`, `what-the-first-call-is-like`. **Or** comment out the `<section id="resources">` block in `templates/page-for-parents.php` until guide bodies are written. | WP admin + page editor for body copy. | ☐ |
| W5 | **Service-area pages** (when ready) — title "Teen IOP in &lt;Metro&gt;, NC", template **Service Area**, optional parent page "Service Areas" so URL becomes `/service-areas/teen-iop-<metro>/`. Optional Custom Fields: `metro_name`, `travel_context`. | WP admin + page editor. | ☐ |
| W6 | **Append security headers** — paste `jtree-wp-theme/htaccess-security.txt` into the WP root `.htaccess` *outside* the `# BEGIN WordPress` / `# END WordPress` managed block. | SFTP. | ☐ |
| W7 | **Bump `JTREE_THEME_VERSION`** in `functions.php` whenever you ship a JS/CSS change without a full theme reupload. Currently `2.0.0`. | Edit the constant. | ☐ |

### Google Sheets (one-time, manual)

| # | Item | Why | Status |
|---|---|---|---|
| S1 | Add header `status` to column **K** of the Leads sheet | Partial-capture writer expects column K. Existing rows leave it blank — read blank as `lead`. | ☐ |
| S2 | Add header `session_id` to column **L** | Lets admissions correlate a partial → completed lead by sorting on this column. | ☐ |
| S3 | If anyone has built a pivot/script/Looker view on the sheet, **review and update it** for the new columns | The watchdog cron has been updated to filter partials, but downstream consumers haven't. | ☐ |

### Founder copy still needed

Code is ready for these; only the words are missing.

| # | Item | Where | Status |
|---|---|---|---|
| F1 | Careers — hero copy, openings list, application-section intro | `templates/page-careers.php` (search for `[DRAFT — founder edit]`) | ☐ |
| F2 | Four Parent Guide bodies (W4) | WP page editor for each child page | ☐ |
| F3 | Service-area pages — pick 2–3 metros, write 200–400 words each | WP page editor for each | ☐ |
| F4 | `og-image.jpg` — 1200×630, replace `assets/brand/og-image.README.txt` | SFTP into `assets/brand/` | ☐ |
| F5 | Real photography — teen lifestyle photos for hero treatments (brand collage covers heroes today) | Replace `<img>` references in templates as photos arrive | ☐ |
| F6 | Real street address for `LocalBusiness` schema | `inc/seo.php` (currently "Apex, NC 27502" without street number) | ☐ |
| F7 | Privacy policy — counsel review (current copy is honest but not legal-reviewed) | `templates/page-privacy.php` | ☐ |
| F8 | Confirm `jtreehealth.com` is the production domain | All references in code default to this — replace if different | ☐ |

### Engineering follow-ups (after launch is fine)

| # | Item | Why | Status |
|---|---|---|---|
| E1 | Activate Ritten end-to-end once V7 lands | Move CRM destination off Sheets fallback | ☐ |
| E2 | Verify `inc/seo.php` GTM placeholders are replaced | If using cookie-banner plugin's injection, this may be moot | ☐ |
| E3 | Smoke-test partial capture on production (interact, abandon, see row appear in Sheets within 10s) | Validates the pagehide beacon path under real CDN conditions | ☐ |
| E4 | Resume binary upload on careers form | v1 accepts a paste-a-link URL only. Real attachments need multipart parsing + size limits + virus scan. | ☐ |
| E5 | Stale CLAUDE.md note about `site/preview/` | The repo doc references a parallel preview codebase that no longer exists. Remove the line in `CLAUDE.md` so future contributors don't hunt for it. | ☐ |
| E6 | Decide whether to track partial-capture rate in GA4 as a custom metric | Today partials write only to Sheets/Ritten — no GA4 event fires. Adding one is a GTM tagging change, not a code change. | ☐ |
| E7 | Consider Upstash Redis for rate limiting at scale | Current limiter is in-memory per Vercel function instance. Env vars are reserved in `.env.example`. | ☐ |
| E8 | Add a structured-data validator to CI (Schema.org JSON-LD + FAQPage) | Catches breakage when copy changes diverge from schema | ☐ |

### Touch points to keep an eye on (operational)

These aren't tasks — they're hooks where something external can affect the system. Worth knowing about during incident triage.

| Surface | What to know |
|---|---|
| **Leads spreadsheet column order** | The API writes to columns A–L positionally. Reordering columns will silently corrupt data. If you need to add columns, append to the right of L. |
| **`session_id` value** | Generated client-side per browser tab. Same id is sent on the partial *and* the eventual full submission, so admissions can match a partial → lead by sorting Sheets on column L. |
| **Watchdog cron filter** | Reads timestamps from column B and status from column K. Partials are excluded from the "no leads in N hours" alert — only `lead` and blank rows count. If you ever rename the status values, update `countRecentLeads` in `lib/sheets.ts`. |
| **Honeypot** | Both forms have a hidden `hp_field`. Bot fills → silent 200, no row written. Edge case: a real user with assistive tech who fills it gets dropped. The label is `aria-hidden="true"` to minimize this. |
| **Turnstile fall-open behavior** | Verifier returns `true` when `TURNSTILE_SECRET` is unset. This is intentional for dev/staging. Don't ship to production with the secret unset and assume the captcha is gating anything. |
| **GA4 `inquiry_submitted`** | Fires only on `/thank-you/`. Partials do **not** fire it by design. Career applications do not fire it either — they're a separate funnel. |
| **WP + Cloudflare cache** | Clear both after every theme deploy. Versioned by `JTREE_THEME_VERSION` constant — bump it when shipping JS/CSS without changing PHP. |

## Known limitations

- The 6 stub pages (For Parents, What We Treat, Insurance, Contact, Crisis, Privacy) ship with founder-voice placeholder copy. Refine before launch if the founder wants a closer pass.
- Rate limiter is in-memory per Vercel function instance. For higher scale, swap to Upstash Redis (env vars are reserved in `.env.example`).
- Joshie mascot is bundled (`assets/brand/joshie/`) but not yet placed in pages — front-end designer can sprinkle one Joshie per surface, pose chosen by the surface tone (see `jtree-wp-theme/assets/brand/joshie/` plus the rules in the design system README).
