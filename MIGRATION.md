# Migration Runbook — Squarespace → Astro on Vercel

Replaces the public site (currently **Squarespace** at `jtreehealth.com`) with this Astro
site on Vercel. A half-built WordPress/Flywheel path is **abandoned/superseded**.

**The backend does not move.** `jtree-form-api` (Vercel), Resend, Google Sheets,
GA4/GTM, Turnstile, and the Cloudflare DNS zone all stay exactly as they are. Net
change at go-live = **one DNS cutover + one CORS env flip.**

## Stays vs. changes

| Stays untouched | Changes |
|---|---|
| `jtree-form-api` @ `api.jtreehealth.com` (Vercel) | Frontend: Squarespace → this Astro app (Vercel) |
| Resend, Google Sheets, GA4 `G-8M90ZXZ1NW`, GTM `GTM-WGCMNLXH`, Turnstile | DNS `@` + `www`: Squarespace → Vercel |
| Cloudflare DNS zone, Resend DKIM/SPF, Google verification | API `ALLOWED_ORIGINS` → production value |
| `api` subdomain record | Repo: `jtree-wp-theme` removed; Astro app added |

---

## 0 · Pre-flight
- [ ] Inventory current Squarespace URLs; add 301s to `vercel.json` → `redirects` (a couple of common ones are stubbed; `/parent-guide` → `/for-parents` is done).
- [ ] Lower Cloudflare TTL on `@` and `www` to ~5 min for fast rollback.
- [ ] Confirm access: Cloudflare (Eliseo), Vercel (Eliseo), Squarespace registrar (Gabriela/Eliseo).
- [ ] Get the **Turnstile SITE key** (public half; the secret is already in the API project).

## 1 · Repo (this PR)
- Astro app supersedes `jtree-wp-theme` (removed — preserved in git history). `jtree-form-api` untouched.
- Review + merge the PR to `main`.

## 2 · Vercel — new "site" project (separate from the API project)
- Import the repo → **new project**. Root directory = repo root. Framework = Astro (auto-detected).
- **Production env vars:**
  - `PUBLIC_GTM_ID=GTM-WGCMNLXH`
  - `PUBLIC_INQUIRY_API_URL=https://api.jtreehealth.com/api/inquiry`
  - `PUBLIC_TURNSTILE_SITE_KEY=<turnstile site key>`
- **Deployment Protection: OFF** for production.
- Deploy → note the `*.vercel.app` preview URL.

## 3 · Staging verification (before any DNS change)
- [ ] Temporarily append the `*.vercel.app` origin to the **API project's** `ALLOWED_ORIGINS`; redeploy the API.
- [ ] On the preview URL, confirm:
  - Admissions form → row in **"JTree Health · Leads"** Sheet **+** Resend email to `admissions@`; redirect to `/thank-you/`.
  - Interact with a non-PII field then leave without submitting → **partial** row (no PII) in the Sheet.
  - Careers form → email/Sheet.
  - GA4 **Realtime**: pageview + `inquiry_submitted` (after accepting cookies).
  - Turnstile renders + passes; **no CSP errors** in the console.
  - Lighthouse + mobile pass.

## 4 · Cutover (DNS)
- [ ] Flip the **API project** `ALLOWED_ORIGINS` → `https://jtreehealth.com,https://www.jtreehealth.com` (drop the local/preview values); redeploy the API.
- [ ] Vercel site project → **Settings → Domains** → add `jtreehealth.com` + `www.jtreehealth.com`; copy the DNS target Vercel shows.
- [ ] Cloudflare DNS (zone `jtreehealth.com`):
  - Repoint `A @` and `CNAME www` **Squarespace → Vercel** (per Vercel's instructions; usually `CNAME www → cname.vercel-dns.com` and the apex per Vercel's apex guidance). Set **DNS-only (grey cloud)** unless Vercel says otherwise.
  - **Leave** `CNAME api → cname.vercel-dns.com` (the API — do not touch).
  - **Leave** Resend (DKIM/SPF) + Google verification records intact.
- [ ] Verify SSL issues cleanly (Vercel certs; Cloudflare SSL = Full/Full-strict).

## 5 · Post-cutover verification
- [ ] `https://jtreehealth.com` + `www` load the new site; redirects + 404 behave.
- [ ] One **real** test lead end-to-end (Sheet + email) — delete the test row after.
- [ ] GA4 Realtime shows pageview + `inquiry_submitted` on the live domain.
- [ ] Monitor 24–48h, then mark `inquiry_submitted` as a **Key Event** in GA4.
- [ ] Restore DNS TTLs.

## 6 · Decommission
- [ ] Cancel the **Squarespace site** subscription (keep the domain *registration* there; DNS lives at Cloudflare).
- [ ] If a Flywheel site exists, cancel the Tiny plan.

## Rollback
At any point during cutover, revert the Cloudflare `@`/`www` records to Squarespace's
targets. With a 5-min TTL that's a few minutes. `api.*` is never touched, so the API
and lead capture are never at risk.

## Still to do (post-launch, non-blocking)
- **Ritten CRM**: set `RITTEN_API_URL` + `RITTEN_API_KEY` in the API project when ready (currently Sheets fallback — by design).
- Founder's real origin story (`about.astro` has an on-brand draft), team bios/photos, legal review of `privacy`, a custom-designed OG image (current is a hero crop).
- Full Squarespace → new-URL 301 map in `vercel.json`.
- Optional: TinaCMS for non-developer content editing.
