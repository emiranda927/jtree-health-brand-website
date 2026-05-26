# JTree Health — WordPress Theme Deployment

End-to-end deployment guide for the GeneratePress child theme. For the full launch sequence (form API + DNS + analytics), see `../DEPLOYMENT.md` at the website root.

## Prerequisites

- Flywheel site provisioned on the Tiny plan (or other WP host with PHP 8.0+, WordPress 6.x)
- GeneratePress parent theme installed and activated
- SFTP / SSH credentials for the host
- `https://api.jtreehealth.com` already deployed (see `../jtree-form-api/README.md`)

## Upload paths

```
wp-content/themes/jtree-wp-theme/      ← this directory's contents
```

Two upload methods:

### A. Local desktop app → "Connect to Flywheel" (recommended for first push)

The local site is already running in Local. Open it, click **Connect to Host** in the Local sidebar, sign in to Flywheel, and pick the destination site. Local will push theme files + database in one shot. Best path for both the first push and ongoing iteration if you're editing through Local.

### B. SFTP (use for one-off file uploads or when not using Local)

```bash
# Get credentials from the Flywheel dashboard → site → Advanced → SFTP
sftp <user>@<host>.flywheelsites.com
cd wp-content/themes/
put -r jtree-wp-theme
```

Flywheel does not have a native GitHub Action like WP Engine. If you want git-based deploys, options are: (1) keep editing locally and re-push via Local, (2) use a third-party deploy tool like DeployHQ or Buddy that supports SFTP targets, or (3) push directly via SFTP from a CI step.

## Activate

1. **Appearance → Themes** — activate "JTree Health"
2. **Settings → Permalinks** — set to "Post name", save
3. **Settings → Reading** — set "Home" page as static front page

## Create pages

Create a WordPress page for each template, then under **Page Attributes → Template** select the matching template name:

| URL slug | Template name |
|---|---|
| `/` (home) | Home |
| `/programs/` | Programs |
| `/about/` | About |
| `/admissions/` | Admissions |
| `/thank-you/` | Thank You |
| `/what-we-treat/` | What We Treat |
| `/for-parents/` | For Parents |
| `/for-teens/` | For Teens |
| `/insurance/` | Insurance |
| `/contact/` | Contact |
| `/crisis/` | Crisis Resources |
| `/privacy/` | Privacy Policy |

Note: the last 7 pages are placeholder stubs — content + design are pending.

## Form wiring

`assets/js/form.js` POSTs to `https://api.jtreehealth.com/api/inquiry`. On success it redirects to `/thank-you/`, where GA4 fires the `inquiry_submitted` event. No WordPress code touches the submission.

If the API URL changes, update `assets/js/form.js`. Do not move form handling into WordPress — the separation keeps PHI off the WP host.

## Pre-launch checklist

### WordPress
- [ ] WordPress 6.x installed on PHP 8.0+
- [ ] GeneratePress parent theme active
- [ ] JTree Health child theme uploaded and activated
- [ ] Permalinks set to "Post name"
- [ ] All 12 pages created and assigned to correct templates
- [ ] Home set as static front page

### DNS & SSL (Cloudflare)
- [ ] `jtreehealth.com` zone added
- [ ] A/AAAA or CNAME records pointing root + `www` at Flywheel (target shown in Flywheel dashboard → site → Domains)
- [ ] CNAME `api.jtreehealth.com` → `cname.vercel-dns.com`
- [ ] Flywheel: domain added in dashboard → site → Domains, "Let's Encrypt" SSL issued
- [ ] Vercel: `api.jtreehealth.com` added under Project → Domains
- [ ] Cloudflare SSL mode = **Full (strict)**, "Always Use HTTPS" enabled
- [ ] HSTS preload checked at https://hstspreload.org/

### Form API
- [ ] `api.jtreehealth.com` returns 200 on `GET /api/health`
- [ ] CORS env `ALLOWED_ORIGIN=https://jtreehealth.com` matches the production origin
- [ ] End-to-end test: submit live form → row in Sheets (or Ritten) + email to admissions

### Security
- [ ] securityheaders.com scan ≥ A grade
- [ ] `wp-login.php` brute-force protection enabled (Flywheel default + 2FA on admin accounts)
- [ ] XML-RPC blocked (test: `POST /xmlrpc.php` returns 403/empty)
- [ ] No WP version in source view
- [ ] File editor disabled in wp-admin (`DISALLOW_FILE_EDIT` set in `inc/security.php`)

### Analytics (see `docs/analytics-config.md`)
- [ ] GA4 property created with correct privacy settings (IP anonymization ON, Google Signals OFF)
- [ ] GTM container created, ID swapped into `inc/seo.php` or theme options
- [ ] `inquiry_submitted` event fires only on `/thank-you/`
- [ ] Cookie banner installed, defaults to deny non-essential

### SEO
- [ ] Schema.org JSON-LD validated → https://search.google.com/test/rich-results
- [ ] OpenGraph validated → https://developers.facebook.com/tools/debug/
- [ ] Sitemap available (Yoast / Rank Math / WP core)
- [ ] `robots.txt` sane (allow crawl, no `Disallow: /` left behind)
- [ ] Search Console verified, sitemap submitted

### Content
- [ ] Phone `(919) 335-5053` correct site-wide
- [ ] Apex, NC address correct
- [ ] Insurance carrier list current
- [ ] Crisis bar 988 / Text HOME to 741741 verified
- [ ] Forms tested end-to-end with real email + phone

### Performance
- [ ] PageSpeed Insights mobile score ≥ 90
- [ ] Images compressed (WebP where supported)
- [ ] Brotli/Gzip on at Flywheel + Cloudflare
- [ ] Browser caching headers set

## Post-launch

- Monitor `api.jtreehealth.com/api/health` via UptimeRobot (1-min interval)
- Watchdog cron emails `OWNER_ALERT_EMAIL` if zero leads land in 24h Mon–Fri
- Weekly GA4 review during first month
- Monthly: re-run securityheaders.com, check WP / GeneratePress / plugin updates
