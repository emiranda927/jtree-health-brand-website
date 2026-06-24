# JTree Health Website — Agent Handoff

A new-engineer-or-agent briefing for picking up this project cold. Read this end-to-end before touching anything.

---

## 1. What this is

**JTree Health** is an adolescent IOP (and soon PHP) mental-health clinic in Apex, NC, rebranding from **Simply Teens Outpatient** in mid-June 2026. The website is a brand-site + lead-capture funnel. Founder **Gabriela Miranda** (MBA Stanford, MPA Harvard Kennedy School — owner / operator, not a clinician) built JTree in memory of her brother Josh, an Iraq War veteran who died in 2023 after years of struggle with PTSD and addiction. Josh found peace in Joshua Tree National Park; the practice is named for both him and the desert tree that grows in some of the harshest conditions on earth. That personal origin sets the brand voice.

The acquisition is structured as a clean handoff: Tina Kinnan (founder of Simply Teens) is selling the practice to Gabriela. Most of the existing clinical team — Caitlyn Dowdy (Executive Director, MS, LCMHCS), Beth Bertram (Program Manager, LCSW), Alayna West (Mental Health Therapist, MA, LCMHCA), Corissa Phannenstill (Qualified Professional), plus three supervised interns — is staying through the transition.

**Brand voice rules (non-negotiable):**
- Warm, plain, direct. Human-grounded register.
- Primary CTA site-wide: **"Start the Conversation"** (teen-facing pages use "Talk to someone" instead).
- Form-response promise is standardized to **"within 2 to 4 business hours"** everywhere it appears.
- Never use "world-class," "cutting-edge," "passionate about," "transformative," "journey to wellness," or any of the AI-pattern phrases catalogued in this project. Don't make promises we can't keep (no specific group-size caps, no fabricated outcome stats).
- The Josh-origin surfaces in exactly two sanctioned places: the home page founder quote (§ 05) and the /about/ page hero. Tone is reflective, not heavy.

**Audience:** parents/guardians of teens 10–17. Secondary: teens themselves (own page: /for-teens/). Tertiary: clinical referrers.

**Phone:** (919) 335-5053 · **Address:** 800 West Williams St., STE 203, Apex, NC 27502
**In-network:** BCBS, Cigna/Evernorth, Aetna, Tricare. (UHC and NC Medicaid intentionally not listed — both were claimed in earlier drafts but the founder decided to omit until contracts are confirmed.)
**Hours (current site copy reflects the post-rebrand summer schedule starting mid-June 2026):**
- **IOP (open today):**
  - In-person group blocks in Apex: Monday, Tuesday, Thursday 4:00–7:00 PM
  - Virtual group blocks: Tuesday, Thursday, Saturday 9:00 AM–12:00 PM
  - Each teen attends three blocks per week (mix of in-person and virtual)
  - Individual + family therapy: Wednesday and Friday (no groups)
  - The earlier school-year schedule (Mon–Thu 3:15–6:30 PM) is no longer reflected anywhere in site copy; confirm with founder if it returns as the default at school's start in fall.
- **PHP (launching Fall 2026):** Mon–Fri 9 AM–3 PM. Labeled "Coming soon" everywhere it appears on the site.
- **Office hours (phone/email):** Mon–Fri 9 AM–5 PM ET.
- **Response commitment:** form submissions get a callback within 2–4 business hours.

**Accreditation:** CARF-accredited and state-licensed in NC. The accreditation transfers with the entity acquisition (verify timing with CARF before launch claims).

**Clinical model:** DBT, polyvagal theory, Safe and Sound Protocol (SSP), STARR Commonwealth trauma programs, resilience-based therapies. LGBTQ+-affirming, neurodivergent-friendly.

---

## 2. Repository layout

```
website/                          ← this repo (github.com/emiranda927/jtree-health-brand-website)
├── DEPLOYMENT.md                 ← end-to-end launch guide (READ THIS for go-live)
├── AGENT_HANDOFF.md              ← you are here
├── jtree-wp-theme/               ← WordPress GeneratePress child theme (production)
│   ├── style.css  functions.php
│   ├── inc/                      ← security.php, forms.php, seo.php
│   ├── templates/                ← 12 page-*.php + partials/ (crisis-bar, header-nav, site-footer)
│   ├── assets/css/               ← colors_and_type.css + site.css + wp-glue.css + home.css
│   ├── assets/js/                ← form.js (admissions/contact only), nav.js (sitewide)
│   ├── assets/fonts/             ← locally-bundled variable fonts (no Google Fonts request)
│   ├── assets/brand/             ← logos, collage PNGs, mascot assets
│   ├── docs/                     ← analytics-config.md, gtm-container.json
│   ├── DEPLOY.md                 ← WP-specific deploy checklist
│   └── README.md
├── jtree-form-api/               ← Vercel serverless TypeScript API (production)
│   ├── api/                      ← inquiry.ts, health.ts, watchdog.ts
│   ├── lib/                      ← validate.ts, ritten.ts, sheets.ts, email.ts, rateLimit.ts, logger.ts
│   ├── test/                     ← vitest suite (39 tests, all passing)
│   ├── vercel.json               ← cron config
│   ├── .env.example              ← every env var documented
│   └── README.md
├── JTree_BrandKit_Full.pdf       ← brand kit (colors, type, motifs)
├── JTree_Website_Copy_Edit.docx.md  ← all approved copy (already applied to site)
└── Marketing_v3.pdf              ← marketing materials reference
```

**Single codebase:** `jtree-wp-theme/` is the production target and the only UI source of truth. Local design review happens via a WordPress local-dev environment (Local by Flywheel, LocalWP, etc.) pointed at the theme directory. The static HTML preview that previously lived at `preview/` was retired — its job is now done by local WP dev plus the impeccable design-system files (`/PRODUCT.md`, `/DESIGN.md`).

---

## 3. Tech stack

| Layer | Tech | Notes |
|---|---|---|
| Frontend (production) | WordPress 6.x + GeneratePress parent + JTree child theme | PHP 8.0+ |
| API | Vercel serverless functions, TypeScript, Zod | Node 22+ |
| CRM | Ritten (stub-ready, falls back to Sheets) | See `jtree-form-api/lib/ritten.ts` |
| Email | Resend | Transactional, `noreply@jtreehealth.com` |
| Storage | Google Sheets (lead fallback) | Service account auth |
| Rate limit | In-memory per Vercel instance | Upstash Redis env vars reserved for swap |
| DNS / WAF | Cloudflare | Full-strict SSL |
| Hosting (web) | Flywheel (Tiny plan) | Owned by WP Engine; same managed-WP stack. Any PHP 8.0+ host works |
| Hosting (API) | Vercel | `api.jtreehealth.com` |
| Analytics | GA4 via GTM | Privacy-first config, see `docs/analytics-config.md` |

**Privacy guardrails (non-negotiable):**
- API logs **never** include PII — only `lead_id`, timestamps, delivery statuses, error codes.
- Form has **no free-text fields** by design — eliminates accidental PHI ingestion.
- Submissions go directly from browser to `api.jtreehealth.com` — WordPress host never touches lead data.
- Analytics config: IP anonymization ON, Google Signals OFF, Ads Personalization OFF, 14-month retention.
- **No** Meta Pixel, TikTok Pixel, Hotjar, FullStory, LogRocket, or any session replay tools.
- **No** form-field capture in GTM.

---

## 4. What's done

### Design + content
- [x] Brand system applied (canonical brand-kit colors, five-typeface stack with locally-bundled variable fonts)
- [x] Impeccable design system installed: `/PRODUCT.md` (strategic), `/DESIGN.md` (visual), `/.impeccable/design.json` (sidecar). Read both before any UI work.
- [x] All approved copy from `JTree_Website_Copy_Edit.docx.md` integrated
- [x] **Full site-wide copy refresh complete (May 2026).** All 12 page templates now have real copy aligned with the rebrand: Home, Programs, About, Admissions, Thank You, For Parents, For Teens, What We Treat, Insurance, Contact, Crisis, Privacy. Plus Careers and Service Area templates. The DBT IOP Curriculum (12-week arc, 6 modules, named DBT skills) is integrated into /programs/ and /for-parents/. The verbatim curriculum line *"You are not broken. Your nervous system is trying to protect you."* is the anchor on /for-teens/.
- [x] SEO head blocks (robots, canonical, OG, Twitter, JSON-LD) via `inc/seo.php`
- [x] Schema.org `MedicalBusiness` JSON-LD via `inc/seo.php` with confirmed street address, single live service (IOP), correct opening hours
- [x] FAQPage JSON-LD on `/insurance/`, `/programs/`, `/what-we-treat/` — 20 total Q&As, eligible for rich results
- [x] `noindex` on `/thank-you/`

### WordPress theme (`jtree-wp-theme/`)
- [x] Design-system CSS at `assets/css/colors_and_type.css` (tokens + @font-face) + `site.css` (layout/components) + `wp-glue.css` (GP reset) + `home.css` (home-only). Enqueue order in `functions.php`.
- [x] GeneratePress header suppression (`generate_construct_header` removed via `init` hook)
- [x] `generate_do_default_template_action` filter so custom templates own layout
- [x] WP admin bar offset CSS (`top: 32px` desktop, `46px` mobile) lives in `wp-glue.css`
- [x] Crisis bar partial (988 + Text HOME 741741)
- [x] Header nav partial with dropdown + mobile hamburger animation
- [x] Site footer partial
- [x] All 12 production page templates have real copy + voice-checked content
- [x] Form rendered via `jtree_render_inquiry_form()` (`inc/forms.php`) with `jth-form` / `jth-input` / `jth-select` / `jth-field-label` / `jth-field-error` classes (matched in `colors_and_type.css` + `site.css`)
- [x] GA4 `inquiry_submitted` event fires only on `/thank-you/` via `wp_head` priority 1
- [x] `inc/security.php` hardening (DISALLOW_FILE_EDIT, XML-RPC off, headers, version removal)

### Form API (`jtree-form-api/`)
- [x] `POST /api/inquiry` — validates with Zod, rate-limits (5/10min/IP), dual-delivery
- [x] `GET /api/health` — UptimeRobot endpoint
- [x] `GET /api/watchdog` — Mon–Fri 6 PM ET cron, alerts on zero-leads-in-24h
- [x] **Ritten integration implemented** — `lib/ritten.ts` posts JSON with Bearer auth, 5s timeout, specific error codes (`RITTEN_TIMEOUT`, `RITTEN_NETWORK_ERROR`, `RITTEN_HTTP_<status>`), falls back to Sheets on any error
- [x] `buildRittenPayload()` maps lead → contact/patient/inquiry sections matching Ritten's documented entity types (verified via Keragon's published Ritten action list)
- [x] Resend admissions email with HTML table
- [x] Google Sheets fallback writer
- [x] Honeypot field (`hp_field`) — silent 200 on bot fill
- [x] Phone normalization to E.164
- [x] Vitest suite: 39 tests, all passing

### Deployment scaffolding
- [x] `.gitignore` files (excludes `node_modules`, `.env`, `.DS_Store`, build output)
- [x] Repo pushed to `github.com/emiranda927/jtree-health-brand-website` (private)
- [x] `DEPLOYMENT.md` — top-level launch guide with phase ordering
- [x] `jtree-wp-theme/DEPLOY.md` — WP-specific checklist
- [x] `jtree-form-api/README.md` — env var table, Ritten payload spec, Vercel commands
- [x] `.env.example` annotated with examples and groupings

---

## 5. What's TODO

### A. Copy review still pending (founder sign-off)
All 12 pages now have real copy. Gabriela hasn't done a final voice-check pass. Specific spots worth her eye:
- Home founder quote (§ 05) — the Josh-origin line. Lands as written; she may want to refine.
- /about/ Founder section — uses verified bio from corporate site (Stanford, Harvard). Worth confirming framing.
- /about/ Clinical team cards — real bios pulled from the existing Simply Teens site. Titles may shift post-acquisition.
- /careers/ — copy adapted from the LinkedIn-style job description; she may want to add or trim specifics.
- /privacy/ — flagged in copy as "counsel-reviewed legal language replaces this summary at launch." Need a real legal review before go-live.

### B. Pre-launch dependencies (waiting on owner / vendor — not engineering)
- [x] ~~Confirm domain~~ — confirmed `jtreehealth.com`. Corporate site lives at jtreehealth.com today (Squarespace); the new clinical site will replace it.
- [ ] **Logo SVG** — pending freelancer (current PNG assets are placeholders)
- [ ] **Real photos** — pending client (currently using placeholder treatments)
- [ ] **`og-image.jpg`** — 1200×630, for Open Graph preview cards
- [x] ~~Street address~~ — confirmed: 800 West Williams St., STE 203, Apex, NC 27502. Already wired into schema, footer, contact page.
- [ ] **CARF accreditation transfer** — confirm with CARF that accreditation transfers cleanly with the entity acquisition before the site's CARF claim goes live.
- [ ] **UHC and NC Medicaid contracts** — currently omitted from in-network claims on the site. If contracts close, add back.
- [ ] **IOP schedule reconciliation** — site copy currently describes the school-year schedule (Mon–Thu 3:15–6:30 PM). Effective mid-June 2026, the schedule shifts to the summer model (Mon/Wed/Thu/Fri evenings + Tue/Thu virtual mornings + Saturday virtual). Update copy + schema in sync with whichever schedule is live at the moment of launch.
- [ ] **Ritten API endpoint + token** — integration code is ready, just needs `RITTEN_API_URL` + `RITTEN_API_KEY` env vars
- [x] ~~Resend domain verification~~ — done; emails arrive from `noreply@jtreehealth.com`
- [x] ~~Google service account JSON~~ — done; service account has Sheets edit access
- [x] ~~GA4 Measurement ID + GTM Container ID~~ — done: `G-8M90ZXZ1NW` / `GTM-WGCMNLXH`, wired and firing

### C. Deployment steps (see `DEPLOYMENT.md` for full sequence)
- [ ] Cloudflare zone + nameserver change at registrar
- [ ] Vercel project import + env vars + custom domain
- [ ] Flywheel site provision (Tiny plan paid for) + GeneratePress install + theme upload
- [ ] DNS records pointing root + www at Flywheel; api at Vercel
- [ ] SSL Full (strict) + HSTS + WAF
- [ ] GA4 + GTM property creation + ID injection
- [ ] Cookie banner plugin install (CookieYes / Complianz / Cookiebot)
- [ ] UptimeRobot on `/api/health`
- [ ] End-to-end form smoke test
- [ ] securityheaders.com A grade + PageSpeed ≥ 90 mobile

### D. Content / QA (low-priority refinement)
- [ ] Asset integration — three new images in `jtree-wp-theme/assets/images/` (two `.webp`, one `.gif`) added but not yet placed in pages. **The user explicitly deprioritized this — do NOT pick it up unless asked.**

---

## 6. Critical conventions an agent must follow

### Section dividers
The default section transition is **whitespace and a background shift**, not an SVG divider. SVG wave dividers between every section are explicitly forbidden by `DESIGN.md` (the *Section-Earns-Divider Rule*) — they flatten section identity and read as generic mental-health-clinic template. Use the provided torn-paper PNGs from `assets/brand/` only when a section change deserves a real moment.

### Section background palette
Section background classes live in `assets/css/colors_and_type.css` and `site.css` and use canonical brand-kit colors (no drift):

- body / default = `--jth-cream` (`#F6F4EC`)
- `.section-bg-pale-lav` = `--jth-pale-lavender` (`#EAE3F5`)
- `.section-bg-pale-sage` = `--jth-pale-sage` (`#DCE9E2`)
- `.section-bg-cream-2` = `--jth-cream-2`
- `.section-bg-dark` = `--jth-deep-green` (`#183B2E`)
- footer = `--jth-deep-green`

### Brand colors (canonical — see `/brand_colors.md` and `/DESIGN.md`)
The canonical hex is in `/brand_colors.md` at the project root. CSS tokens in `colors_and_type.css` mirror them exactly. Acceptance per FRONT_END_REQUIREMENTS §11.1: drift must stay within 2 sRGB units.

```
--jth-deep-green:    #183B2E   (primary, body text, default CTA)
--jth-lime-green:    #C8E869   (teen-facing accent — /for-teens/ only by default)
--jth-lavender:      #B8A7E6   (quote sections, eyebrow fills, twinkle glyphs)
--jth-cream:         #F6F4EC   (page background)
--jth-charcoal:      #1D1D1F   (text on Lime / Sunflower backgrounds)
--jth-sunflower:     #FFD100   (highlight, focus rings, scribble accent)
--jth-pale-lavender: #EAE3F5
--jth-pale-lime:     #EEF6CC
--jth-pale-sage:     #DCE9E2
```

### Single codebase
The static `preview/` directory has been retired. `jtree-wp-theme/` is the only UI source of truth. Local design review happens via a WordPress local-dev environment (Local by Flywheel, LocalWP, etc.).

### WP-specific gotchas
- GeneratePress's built-in header is suppressed via `remove_action('generate_header', 'generate_construct_header')` in `functions.php`. **Do not re-enable it** — the custom `header-nav.php` partial owns the header.
- The `generate_do_default_template_action` filter returns `false` for any `is_page_template()` so custom templates fully control layout. **Don't break this filter.**
- Hrefs in templates use `home_url('/path/')` — never hardcode `.html` paths (a former bug).
- Form classes are `jth-form` / `jth-input` / `jth-select` / `jth-field-label` / `jth-field-error` (the `jth-*` namespace, not BEM `jtree-form__*`). The CSS in `colors_and_type.css` and `site.css` is named to match `inc/forms.php` output. **Don't rename one without the other.**

### Form / API
- Submissions go to **`https://api.jtreehealth.com/api/inquiry`** — a separate origin. CORS is locked to `ALLOWED_ORIGIN` env var.
- Dual delivery: (Ritten OR Sheets) AND Resend email. Both must fail for a 500 response.
- Ritten payload structure is in `buildRittenPayload()` in `lib/ritten.ts`. If Ritten's intake schema differs in production, **only edit the payload builder** — auth, timeouts, error handling all generic.
- Logs **never** contain PII. If you add logging, log only `lead_id` + delivery status + error codes.

### Privacy / analytics
- Single conversion event: `inquiry_submitted` on `/thank-you/` only. Never expand this without explicit approval.
- No tracking pixels beyond GA4. If asked to add Meta/TikTok/etc., push back and reference this doc.

---

## 7. How to run things

### Local WordPress dev
Use a local WP dev environment (Local by Flywheel, LocalWP, DDEV, etc.). Symlink or copy `jtree-wp-theme/` into the WP `wp-content/themes/` directory of the local site, install GeneratePress as the parent theme, and create the 12 pages assigning the matching templates. See `jtree-wp-theme/README.md` for setup details.

### Form API tests
```bash
cd /Users/eliseo/Projects/jtree-health-website/site/jtree-form-api
npm install
npm test          # vitest, 39 tests
npm run dev       # vercel dev on :3000
```

### Form API local dev
Set `.env` from `.env.example`. With `RITTEN_API_URL` empty, Sheets fallback path is exercised. With `RESEND_API_KEY` empty, email delivery throws.

### Deploy
See `DEPLOYMENT.md` for the full sequence. TL;DR:
- Form API: push to GitHub `main` → Vercel auto-deploys
- WP theme: easiest first push is via Local desktop app → "Connect to Flywheel" → push. For ongoing edits, SFTP from the Flywheel dashboard works (Flywheel doesn't have a native GitHub action like WP Engine).

---

## 8. Open decisions (need owner input)

| Decision | Why it matters | Default if not asked |
|---|---|---|
| ~~Final domain~~ | ✅ Resolved — `jtreehealth.com` confirmed | — |
| ~~Stub pages — finish or hide?~~ | ✅ Resolved — all 12 pages now have real copy | — |
| Ritten launch coupling | Block launch on Ritten? | Launch with Sheets-only, add Ritten when ready |
| Cookie banner choice | GDPR/CCPA compliance | CookieYes free tier |
| GitHub repo visibility | Public vs. private | Private (current setting) |
| ~~IOP schedule version at launch~~ | ✅ Resolved — site now reflects the post-rebrand summer schedule (in-person Mon/Tue/Thu afternoons + virtual Tue/Thu/Sat mornings, with Wed/Fri reserved for individual + family) | — |
| ~~Brand-name in body copy~~ | ✅ Resolved — full "Joshua Tree Health" used throughout body copy and schema | — |
| Schedule revert at fall (school year)? | The school-year schedule (Mon–Thu 3:15–6:30 PM after school) may return when schools resume in fall. Site currently describes the summer schedule only. | Confirm with founder ahead of fall whether the schedule changes back or stays in the summer rotation. |
| Clinical-model elements to surface beyond /careers/ | /careers/ already names STARR Commonwealth + Resilience-Based + LGBTQ+-affirming + neurodivergent-friendly. Other parent/teen pages don't yet. | Founder said "don't worry about the STARR stuff" — leave as-is unless that changes. LGBTQ+-affirming and neurodivergent-friendly may still be worth adding to /what-we-treat/ and /for-teens/ as parent-trust signals. |

---

## 9. Pointers, not gospel

- **Conversation transcripts** that produced this work: `/Users/eliseo/.claude/projects/-Users-eliseo-Projects-jtree-health-website/*.jsonl`. You usually don't need to read these — this doc plus the code is the source of truth. Reach for them only if you need to reconstruct *why* a specific decision was made.
- **Memory files**: `/Users/eliseo/.claude/projects/-Users-eliseo-Projects-jtree-health-website/memory/` — `MEMORY.md` is the index; individual memory files document long-running preferences and project state.
- **The user is the founder's spouse** (Eliseo Miranda). Not a coder by trade. Communicate in plain language; don't dump terminal output unless asked. Confirm before destructive ops.

---

*Last updated: 2026-05-26. If you make significant changes, update this doc.*
