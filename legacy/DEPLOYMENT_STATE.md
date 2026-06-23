# JTree Health — Deployment State (2026-05-26)

Snapshot for picking up the launch work in a new Claude session. Use this alongside `FRONT_END_REQUIREMENTS.md` (designer brief) and `site/AGENT_HANDOFF.md` (project architecture).

---

## 1. Where we are at a glance

| Block | State |
|---|---|
| Form API on Vercel | ✅ Live; `/api/health` 200; form→Sheets proven end-to-end |
| Google Sheets (lead fallback) | ✅ Service account + sheet shared; rows landing on submit |
| Resend (email) | ✅ Domain `jtreehealth.com` verified; emails arriving from `noreply@jtreehealth.com` |
| Google Workspace | ✅ Eliseo provisioned as Super Admin; `admissions@` and `noreply@` exist as aliases on `eliseo@` |
| Cloudflare DNS | ✅ Zone owned by Eliseo (`eliseo@jtreehealth.com`); nameservers switched at Squarespace; old (orphan) Cloudflare account abandoned |
| GA4 property | ✅ Property created, `G-8M90ZXZ1NW`, privacy settings locked (Google Signals off, location off) |
| GTM container | ✅ `GTM-WGCMNLXH` published; Google tag + GA4 Event tag (`inquiry_submitted`) live |
| WP theme — GTM wiring | ✅ Committed to GitHub `main` |
| Local WP rendering | ✅ All 12 pages render at `http://jtree-local.local/` |
| Brand kit | ✅ At workspace root (`brand kit images/`, `jth_logo_assets_v4/`, `brand_colors.md`) |
| Front-end requirements doc | ✅ `FRONT_END_REQUIREMENTS.md` at workspace root |
| Flywheel production site | ❌ Not provisioned (Eliseo on Tiny plan — site needs to be created) |
| Cloudflare hardening | ❌ Not done (SSL Full strict, HSTS, WAF, Bot Fight) |
| Production DNS records | ❌ Not done (`@`, `www` → Flywheel; `api` → Vercel custom domain) |
| Vercel `ALLOWED_ORIGIN` | ⚠️ Currently `http://jtree-health.local` for testing — must flip to `https://jtreehealth.com` before launch |
| Cookie consent banner | ❌ Not installed |
| GA4 key-event flag | ⏳ Event fires + shows in Realtime; mark-as-key-event deferred until GA4 processes events (~24 hrs) |
| Agency decision | ⏳ Galactic Fed proposal received; Cardinal counter-evaluation not done |

---

## 2. Accounts, IDs, and where credentials live

**Don't paste credential values in chat. Just know where they live.**

| Service | Account | Where credentials are |
|---|---|---|
| Vercel | Eliseo's account | Vercel UI; project name visible in dashboard |
| GitHub | `emiranda927/jtree-health-brand-website` | Local git remote `origin` |
| Google Cloud | Project "JTree Health Forms" under Eliseo's personal Google | Service account JSON stored in Vercel env var `GOOGLE_SERVICE_ACCOUNT_JSON` |
| Google Sheet | "JTree Health · Leads" | ID stored in Vercel env var `GOOGLE_SHEETS_ID`; shared with the service account email |
| Resend | Account on Eliseo's personal email | API key stored in Vercel env var `RESEND_API_KEY` |
| Google Workspace | Admin = `eliseo@jtreehealth.com` (Super Admin) | `admin.google.com` |
| Cloudflare | `eliseo@jtreehealth.com` | cloudflare.com |
| Squarespace (domain registrar) | Gabriela owns; Eliseo has access | domains.squarespace.com |
| GA4 | `eliseo@jtreehealth.com` | analytics.google.com; Measurement ID `G-8M90ZXZ1NW` |
| GTM | `eliseo@jtreehealth.com` | tagmanager.google.com; Container ID `GTM-WGCMNLXH` |
| Flywheel (WP host) | Eliseo on **Tiny plan** — site not yet provisioned | getflywheel.com (Local Connect can push from the Local desktop app) |

### Vercel env vars currently set (production)

- `RESEND_API_KEY`
- `ADMISSIONS_EMAIL` = `eliseo@jtreehealth.com`
- `OWNER_ALERT_EMAIL` = `eliseo@jtreehealth.com`
- `GOOGLE_SHEETS_ID`
- `GOOGLE_SERVICE_ACCOUNT_JSON`
- `ALLOWED_ORIGIN` = `http://jtree-health.local` ⚠️ **must change to `https://jtreehealth.com` before launch**
- `RITTEN_API_URL` / `RITTEN_API_KEY` — **unset** (Sheets fallback active)

---

## 3. Architecture map

- **Front-end** (WP theme): `site/jtree-wp-theme/` — GeneratePress child theme. Renders all 12 page templates.
- **Form API**: `site/jtree-form-api/` — Vercel serverless. POST `/api/inquiry` → validates → routes to Sheets (since Ritten not yet configured) AND Resend. CORS gated to `ALLOWED_ORIGIN`.
- **Local dev**: WordPress in Local app at site `jtree-local`, URL `http://jtree-local.local/`. Theme is symlinked from `site/jtree-wp-theme/` so edits are live.
- **Production hosting**: Flywheel (Tiny plan) for the website; Vercel already hosts the API.
- **DNS**: Cloudflare zone, nameservers `nova.ns.cloudflare.com` + `darl.ns.cloudflare.com`.

---

## 4. Open decisions

### 4.1 Agency vs freelance vs DIY

**Galactic Fed** sent a $8,500 website proposal + ~$36k/yr SEO retainer + PPC. Generalist agency. Not yet declined or counter-proposed. Detailed read in conversation history; key points:
- Proposal is competent but generalist (no healthcare-specific portfolio cited)
- 12-month commits on SEO and PPC retainers
- Would start from zero, ignoring the existing build

**Cardinal Digital Marketing** (healthcare specialist) — not yet contacted. Worth a 30-min call to get their "polish + launch" engagement quote for comparison. They specialize in behavioral-health sites.

**Hybrid path** = freelance designer (~$3.5k) + freelance WP developer (~$2k). Cheapest, more management overhead.

Status: **awaiting decision**. Doesn't block deployment work; both paths can proceed in parallel.

### 4.2 Cookie banner choice

CookieYes / Complianz / Cookiebot — pick one. Free tier on each is fine. Defaults to deny non-essential cookies.

### 4.3 Domain ownership / billing

Gabriela owns the Squarespace domain. Eliseo has access but acquisition entity setup is still in flux (Joshua Tree Health Inc, C-Corp, NC).

---

## 5. Next steps in priority order

When picking up:

1. **Provision the Flywheel site** (~30 min — Tiny plan already paid for). Create a new site in the Flywheel dashboard with a temporary `<sitename>.flywheelsites.com` URL. After this, the production site has a real URL we can point DNS at.
2. **Push theme to Flywheel** + create the 12 pages with template assignments (mirror of what's been done locally). Easiest path: open the Local site in the Local desktop app → click "Connect to Flywheel" → push. Alternative: SFTP `jtree-wp-theme/` into `wp-content/themes/` using credentials from the Flywheel dashboard.
3. **Production DNS at Cloudflare**: `A @` and `CNAME www` → Flywheel target (per the "Add domain" instructions in Flywheel's dashboard, typically `<sitename>.flywheelsites.com`); `CNAME api` → `cname.vercel-dns.com`.
4. **Vercel custom domain**: add `api.jtreehealth.com` to the Vercel project.
5. **Switch `ALLOWED_ORIGIN`** in Vercel from `http://jtree-health.local` to `https://jtreehealth.com`. Redeploy.
6. **Cloudflare hardening**: SSL Full (strict), HSTS, "Always use HTTPS", WAF managed ruleset, Bot Fight Mode, custom rule blocking `xmlrpc.php`.
7. **Cookie consent banner**: install plugin, configure to deny non-essential by default.
8. **Final smoke test** on production domain: form submission lands in Sheet + email; GA4 Realtime shows pageview + `inquiry_submitted`.
9. **Mark `inquiry_submitted` as key event in GA4** once it appears in Recent events (~24 hrs after first fire).
10. **Decline or counter-propose Galactic Fed** in parallel; contact Cardinal for healthcare-specialist quote.

### Lower priority / optional

- **Multi-origin CORS** support in `inquiry.ts` so local + staging + production can all submit. Currently we just flip `ALLOWED_ORIGIN`.
- **Untick Google products & services** data sharing in GA4 (Admin → Account → Data Sharing Settings).
- **Stub page content** for the 7 pages that aren't fully designed (For Parents, For Teens, What We Treat, Insurance, Contact, Crisis, Privacy). Designer handoff per `FRONT_END_REQUIREMENTS.md`.

---

## 6. Things to know that bit us during this session

- **Local site name** is `jtree-local`, not `jtree-health`. Site path: `/Users/eliseo/Local Sites/jtree-local/app/public/`. The local site's theme is symlinked from the project source.
- **DNS for `jtreehealth.com` lives at Cloudflare**, not Squarespace. The Squarespace DNS records added before the Cloudflare migration were orphaned. New Cloudflare account is Eliseo's; old account (whoever set up `jtreehealth.com` originally) is abandoned.
- **Vercel Deployment Protection** must be off for production (or set to "Only preview deployments") — it blocks form submissions otherwise.
- **Vercel needs `"type": "module"` in package.json** for the form-API project; this was a fix during initial deploy.
- **GTM has 3 entities**: Google Tag (GA4 base, fires on All Pages), GA4 Event tag (`inquiry_submitted`, fires on CE trigger), and CE Custom Event trigger.
- **The thank-you handler in `functions.php`** pushes `dataLayer.push({event:'inquiry_submitted',...})`, not `gtag('event',...)`, because GTM's Custom Event trigger listens for dataLayer events.

---

## 7. Resume prompt for new conversation

Paste this as your first message in a new Claude Code session opened at `/Users/eliseo/Projects/jtree-health-website/`:

> Resuming JTree Health website deployment. Read `site/DEPLOYMENT_STATE.md` for full context. Short version:
>
> - Vercel API live, form→Sheets proven, Resend email working (domain verified)
> - Cloudflare DNS owned by me, nameservers switched
> - GA4 + GTM configured (`G-8M90ZXZ1NW` / `GTM-WGCMNLXH`), event firing, wired in theme and pushed to GitHub
> - Local WP rendering all 12 pages
> - **Not yet done**: Flywheel site provision (Tiny plan paid for), production DNS records (root/www→Flywheel, api→Vercel), Cloudflare hardening, cookie banner, `ALLOWED_ORIGIN` switch from local to production, final smoke test
>
> Memory files already capture preferences (autonomous runs once a numbered sequence is agreed, plain language, designer handoff, etc.).
>
> Next step I want to tackle: **[Flywheel site provision / Cardinal email / something else]** — fill in whichever.

---

*Last updated: 2026-05-26. Update this file if you make significant changes between sessions.*
