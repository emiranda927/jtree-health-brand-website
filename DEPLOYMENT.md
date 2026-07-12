# Joshua Tree Health — Deployment Plan & State

**Last verified: 2026-07-11** (every ✅ below was tested live that day, not assumed).
This file supersedes `MIGRATION.md` (see git history). Audience: Eliseo, the designer,
and any future engineer/agent. The goal state: after the designer's front-end pass,
launch = the short checklist in §4. Everything else is already done and proven.

---

## 1. Architecture (what runs where)

| Piece | Where | Status |
|---|---|---|
| **Website** (this repo, Astro 5 static) | Vercel project **`jtree-health-site`** → https://jtree-health-site.vercel.app | ✅ live (temp domain) |
| **Form API** (`jtree-form-api`, separate repo¹) | Vercel project **`jtree-health`** → **https://api.jtreehealth.com** | ✅ live, healthy |
| Lead storage | Google Sheet "JTree Health · Leads" (service-account write) | ✅ proven 2026-07-11 |
| Lead email | Resend → `ADMISSIONS_EMAIL` (from `noreply@jtreehealth.com`, domain verified) | ✅ proven 2026-07-11 |
| Analytics | GA4 `G-8M90ZXZ1NW` via GTM `GTM-WGCMNLXH`, Consent Mode v2 — **analytics ON by default (opt-out)**; ad storage/personalization always denied | ✅ wired + container published |
| Cookie banner | Custom `CookieConsent.astro` — opt-out notice; **Decline turns analytics off** and the choice persists | ✅ verified 2026-07-12 |
| DNS zone | Cloudflare (Eliseo's account) — `api` CNAME → Vercel already live | ✅ |
| `jtreehealth.com` root/www | Still Squarespace (old corporate site) | ⏳ cutover at launch |
| CRM (Ritten) | Not configured — **Sheets fallback active by design** | optional, post-launch |
| Turnstile CAPTCHA | Not configured on either side — API "falls open" (honeypot + rate-limit still active) | optional hardening |

¹ Form API code lives at `jtree-health-website/site/jtree-form-api` (GitHub `emiranda927/jtree-health-brand-website`). It needs **no changes** for launch.

## 2. What was verified end-to-end on 2026-07-11

One clearly-marked TEST lead submitted on the **live** site (`/admissions`):

1. CORS preflight allowed for the live origin ✅
2. API validated + assigned `lead_id JT-wMu60O7UjJ` ✅
3. **Row written to Google Sheets** ✅ (test row — first name "TEST" — safe to delete)
4. **Admissions email sent via Resend** ✅
5. API responded 200 → browser redirected to `/thank-you/` ✅
6. `inquiry_submitted` pushed to dataLayer exactly once ✅
7. Cookie banner (re-verified 2026-07-12 after the opt-out change): fresh visitor →
   analytics granted by default; Decline → denied + persisted; returning declined
   visitor stays denied with no banner ✅

Also verified: `/api/health` 200; careers + partial-capture endpoints exist and their
schemas match the site's forms exactly; GTM container serves (355 KB, published);
sitemap + robots point at the final domain; old routes 301 (`/for-teens`, `/programs`).

Note: GA4 network hits can't be observed from the sandboxed test browser (it blocks
trackers) — the wiring is verified in-page and was previously seen in GA4 Realtime (May 2026).
Re-check Realtime once on the final domain (checklist §4).

## 3. Configuration map (no secret values here)

**Site project `jtree-health-site`** — no env vars set; code defaults are correct:
`PUBLIC_GTM_ID=GTM-WGCMNLXH`, `PUBLIC_INQUIRY_API_URL=https://api.jtreehealth.com/api/inquiry`,
`PUBLIC_TURNSTILE_SITE_KEY` empty (widget off). Set these in Vercel only if they must diverge.

**API project `jtree-health`** (all set in Vercel, encrypted): `RESEND_API_KEY`,
`ADMISSIONS_EMAIL`, `OWNER_ALERT_EMAIL`, `GOOGLE_SHEETS_ID`, `GOOGLE_SERVICE_ACCOUNT_JSON`,
and `ALLOWED_ORIGINS = https://jtreehealth.com, https://www.jtreehealth.com, https://jtree-health-site.vercel.app`
(updated 2026-07-11: the temp `.vercel.app` origin was added **for pre-launch testing
only** — remove it at cutover, checklist §4; the dead Flywheel entry was removed).
Unset by design: `RITTEN_API_URL/KEY`, `TURNSTILE_SECRET`.

Security headers + CSP live in `vercel.json` (this repo). The temp domain
`jtree-health-site.vercel.app` sends `X-Robots-Tag: noindex` via a host-scoped rule —
the real domain will not (added 2026-07-11).

### 3a. GitHub repo & auto-deploy hooks — current truth (2026-07-12)

- The GitHub repo is **`emiranda927/jtree-health-brand-website`**. PR #4
  ("Migrate frontend: Astro site supersedes WordPress theme") merged ~2026-06-23:
  `main` now has the **Astro app at the repo root**, `jtree-form-api/` beside it,
  and the old WP theme under `legacy/`.
- ⚠️ **Repo `main` is ~3 weeks behind production.** Everything since July 1 (copy
  rebuild to the new sitemap, nav/CTA fixes, headshot, deployment prep, opt-out
  consent) was deployed via CLI from a detached working copy
  (`~/Projects/jtree-health`) and never pushed. **Do not push `main` to any
  git-connected Vercel project, and do not (re)connect git, until the sync branch
  below is merged** — otherwise the June site can be deployed over production.
- A local branch **`sync/astro-2026-07-12`** (in `~/Projects/jtree-health-website/site`)
  brings repo `main` to exact parity with production. After it's pushed + merged:
  1. Vercel → `jtree-health-site` → Settings → Git: connect to the repo, Root
     Directory `.` (or run `npx vercel git connect` from the linked folder).
  2. Vercel → `jtree-health` (API): confirm its Git connection, Root Directory
     `jtree-form-api`.
  3. Leave "Ignored Build Step" on **Automatic** on both projects so a push that
     only touches the site doesn't rebuild the API, and vice versa.
  4. From then on: **deploys happen by pushing to `main`** (PRs get preview URLs);
     the CLI (`npx vercel --prod --yes`) remains a manual fallback.
  The detached `~/Projects/jtree-health` folder is then retired — future work happens
  in the monorepo.

## 4. LAUNCH-DAY CHECKLIST (the only remaining work)

Do this after the designer's changes are merged and re-verified (§5). The site launches
**on `jtreehealth.com`** — the `.vercel.app` address is a testing surface only and is
never promoted.

1. **Cloudflare** (dash.cloudflare.com, zone `jtreehealth.com`): lower TTL on `@` and `www` to 5 min (fast rollback).
2. **Vercel** → project `jtree-health-site` → Settings → Domains → add `jtreehealth.com` + `www.jtreehealth.com`. Copy the record targets Vercel shows.
3. **Cloudflare**: repoint `A @` and `CNAME www` from Squarespace to Vercel's targets (DNS-only / grey cloud unless Vercel says otherwise). **Do not touch** `api`, Resend (DKIM/SPF), or Google verification records.
4. Wait for SSL to issue, then smoke-test on `https://www.jtreehealth.com`: pages load, one TEST form submission (delete the row after), GA4 **Realtime** shows page_view + `inquiry_submitted` after accepting cookies.
5. **Retire the testing origin**: remove `https://jtree-health-site.vercel.app` from the API project's `ALLOWED_ORIGINS` (Vercel → `jtree-health` → Settings → Environment Variables) and redeploy the API. The temp domain keeps its `noindex` header regardless.
6. 24–48 h later: GA4 → Admin → Events → mark `inquiry_submitted` as **Key Event**. Restore DNS TTLs.
7. Decommission: cancel the Squarespace **site** subscription (keep domain registration + Cloudflare DNS), cancel Flywheel Tiny if still active.

**Rollback:** revert the two Cloudflare records to Squarespace. `api.*` is never touched, so lead capture is never at risk.

## 5. Designer handoff — rules of the road

- Work happens in the GitHub monorepo (`emiranda927/jtree-health-brand-website`, Astro app at root) **once the sync branch in §3a is merged**. Branch → PR → Vercel preview URL → merge to `main` deploys production. `npm run dev` to preview locally; `npm run build` must pass. Until the sync merges, treat `~/Projects/jtree-health` (CLI deploys) as the source of truth.
- **Copy is contract**: wording follows the founder's copy guide + the brand rules in it (no "real/actually" qualifiers, no teen slang, no invented stats, founder story on About only). Don't regress it while restyling.
- **Do not change** (functional, all verified): the admissions + careers `<form>` field names/IDs, `src/lib/inquiry.ts` / `careers.ts` / `Base.astro` script blocks (GTM, consent, config), `vercel.json` (headers/redirects), `robots.txt`.
- Content placeholders awaiting **Gaby**, not the designer: founder surname check ("Forter"), Josh paragraph on About, mission/vision on Our Culture, team bios/headshots, Learning Hub articles, homepage parent quote confirmation, real outcome stats (only if she can stand behind them).

## 6. Post-launch / optional hardening (not blockers)

- **Turnstile**: create a Cloudflare Turnstile site key/secret; set `TURNSTILE_SECRET` (API project) + `PUBLIC_TURNSTILE_SITE_KEY` (site project); redeploy both. Until then: honeypot + 5-per-10-min rate limit.
- **Ritten CRM**: set `RITTEN_API_URL` + `RITTEN_API_KEY` when contracts are ready — Sheets stays as fallback automatically.
- **Watchdog**: `/api/watchdog` cron (Mon–Fri 6 PM ET) alerts `OWNER_ALERT_EMAIL` if no leads in 24 h — expect alerts to be meaningful only after launch.
- UptimeRobot (or similar) on `https://api.jtreehealth.com/api/health` and the site root.
- Legal review of `/privacy`; custom OG image; full Squarespace→new-URL 301 map in `vercel.json`; consider GitHub + Vercel git integration for the site repo.
