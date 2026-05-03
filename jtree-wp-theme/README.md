# JTree Health - WordPress Theme

GeneratePress child theme for Joshua Tree Health, an adolescent PHP/IOP mental health clinic in Apex, NC.

## Requirements

- WordPress 6.x+
- GeneratePress theme (parent)
- PHP 8.0+

## Installation

1. Install and activate the GeneratePress theme
2. Upload `jtree-wp-theme/` to `wp-content/themes/`
3. Activate "JTree Health" in Appearance > Themes
4. Create WordPress pages and assign the corresponding page templates:
   - Home → `Home` template
   - Programs → `Programs` template
   - What We Treat → `What We Treat` template
   - For Parents → `For Parents` template
   - For Teens → `For Teens` template
   - Insurance → `Insurance` template
   - Admissions → `Admissions` template
   - About → `About` template
   - Contact → `Contact` template
   - Thank You → `Thank You` template
   - Privacy → `Privacy Policy` template
   - Crisis → `Crisis Resources` template
5. Set the Home page as the static front page (Settings > Reading)
6. Configure permalinks to "Post name" (Settings > Permalinks)

## Directory Structure

```
jtree-wp-theme/
├── style.css                  # Theme header (GeneratePress child)
├── functions.php              # Enqueue, menus, template registration
├── inc/
│   ├── security.php           # Security headers, hardening
│   ├── forms.php              # Inquiry form markup + shortcode
│   └── seo.php                # Schema.org JSON-LD, OpenGraph
├── templates/
│   ├── page-home.php
│   ├── page-programs.php
│   ├── page-what-we-treat.php
│   ├── page-for-parents.php
│   ├── page-for-teens.php
│   ├── page-insurance.php
│   ├── page-admissions.php
│   ├── page-about.php
│   ├── page-contact.php
│   ├── page-thank-you.php
│   ├── page-privacy.php
│   ├── page-crisis.php
│   └── partials/
│       ├── crisis-bar.php
│       ├── header-nav.php
│       ├── footer-cta.php
│       └── site-footer.php
├── assets/
│   ├── css/
│   │   └── main.css           # Complete brand design system
│   ├── js/
│   │   └── form.js            # Inquiry form (posts to api.jtreehealth.com)
│   └── images/
│       └── spike-motif.svg    # JTree spike pattern
├── docs/
│   ├── analytics-config.md    # GA4 + GTM configuration rules
│   └── gtm-container.json     # GTM container export template
├── README.md
└── DEPLOY.md
```

## Form

The inquiry form posts to `https://api.jtreehealth.com/api/inquiry` via vanilla JS fetch. It does NOT use WordPress for form handling. The form is available as:

- PHP function: `jtree_render_inquiry_form()`
- Shortcode: `[jtree_inquiry_form]`

## Security

See `inc/security.php` for all hardening measures including:
- DISALLOW_FILE_EDIT
- XML-RPC disabled
- Security headers (HSTS, CSP, X-Frame-Options)
- WP version removal
- Login attempt limiting
- Auto-updates for core + plugins

## Analytics

See `docs/analytics-config.md` for GA4/GTM configuration rules. Key constraints:
- No Meta Pixel, no TikTok Pixel, no session replay
- IP anonymization ON, Google Signals OFF
- Single conversion event `inquiry_submitted` on /thank-you/ only
- Cookie banner defaults to decline non-essential
