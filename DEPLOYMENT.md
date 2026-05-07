# JTree Health — End-to-End Launch Guide

Two repos ship together:

| Repo | Hosts | Domain |
|---|---|---|
| `jtree-form-api/` | Vercel (serverless) | `api.jtreehealth.com` |
| `jtree-wp-theme/` | WP Engine (WordPress) | `jtreehealth.com`, `www.jtreehealth.com` |

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
3. **Deploy** — `vercel --prod` or push to `main`.
4. **Verify** — `curl https://<vercel-url>/api/health` → `200 OK`.
5. **Add custom domain** — Vercel project → Domains → add `api.jtreehealth.com`.

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
5. **Upload** `jtree-wp-theme/` to `wp-content/themes/jtree-wp-theme/` via SFTP, then activate it.
6. **Append the security headers snippet** from `jtree-wp-theme/htaccess-security.txt` to the WP root `.htaccess`. Paste *outside* the `# BEGIN WordPress` / `# END WordPress` managed block — WordPress overwrites that block on every save.
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

## Pre-launch open items (waiting on owner / vendor)

- [ ] Confirm `jtreehealth.com` is the production domain
- [ ] Real photography (brand collage carries the heroes today)
- [ ] `og-image.jpg` (1200×630) — see README in `assets/brand/`
- [ ] Street address for Schema.org `LocalBusiness`
- [ ] Ritten API endpoint + token
- [ ] Resend domain verification (`noreply@jtreehealth.com`)
- [ ] Google service-account JSON + Sheets share
- [ ] GA4 property + GTM container IDs
- [ ] Privacy-policy review by counsel (the placeholder is honest but not legal-reviewed)

## Known limitations

- The 6 stub pages (For Parents, What We Treat, Insurance, Contact, Crisis, Privacy) ship with founder-voice placeholder copy. Refine before launch if the founder wants a closer pass.
- Rate limiter is in-memory per Vercel function instance. For higher scale, swap to Upstash Redis (env vars are reserved in `.env.example`).
- Joshie mascot is bundled (`assets/brand/joshie/`) but not yet placed in pages — front-end designer can sprinkle one Joshie per surface, pose chosen by the surface tone (see `jtree-wp-theme/assets/brand/joshie/` plus the rules in the design system README).
