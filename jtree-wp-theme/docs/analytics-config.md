# JTree Health - Analytics Configuration

## GA4 Configuration

### Property Settings
- **Property Name:** JTree Health
- **Website URL:** https://jtreehealth.com
- **Industry Category:** Health
- **Reporting Time Zone:** Eastern Time (US & Canada)

### Privacy Settings (Non-Negotiable)
- **IP Anonymization:** ON
- **Google Signals:** OFF
- **Ads Personalization:** OFF
- **Data Retention:** 14 months

### Prohibited Tracking
- **No Meta Pixel** - Do not install Facebook/Meta tracking
- **No TikTok Pixel** - Do not install TikTok tracking
- **No Session Replay Tools** - No Hotjar, FullStory, LogRocket, or similar
- **No Form-Field Capture in GTM** - Never capture form field values in Tag Manager

## GTM Configuration

### Container Setup
- **Container Name:** JTree Health - Web
- **Container Type:** Web
- **Container ID:** GTM-XXXXXXX (replace with actual ID)

### Tags

#### GA4 Configuration Tag
- **Tag Type:** Google Analytics: GA4 Configuration
- **Measurement ID:** G-XXXXXXXXXX (replace with actual ID)
- **Trigger:** All Pages

#### GA4 Conversion Event - Inquiry Submitted
- **Tag Type:** Google Analytics: GA4 Event
- **Event Name:** `inquiry_submitted`
- **Trigger:** Page View - Thank You Page
  - Trigger Type: Page View
  - Condition: Page Path equals `/thank-you/`
- **Note:** This is the ONLY conversion event. It fires when a user lands on /thank-you/ after successful form submission.

### Variables
- **Page Path:** Built-in variable (enabled)
- **Page URL:** Built-in variable (enabled)

### Triggers
1. **All Pages** - All page views (for GA4 config tag)
2. **Thank You Page View** - Page View where Page Path = `/thank-you/`

## Cookie Banner

### Requirements
- **Default State:** Decline non-essential cookies by default
- **Essential Cookies:** Always allowed (site functionality)
- **Analytics Cookies:** Requires explicit consent
- **Marketing Cookies:** Not used - do not include this category
- **Implementation:** Use a GDPR/CCPA-compliant cookie banner plugin (e.g., CookieYes, Complianz)

### Consent Mode
- Configure GA4 to respect consent mode
- When consent is denied, GA4 should use cookieless pings (if enabled) or not fire at all

## DataLayer Events

### inquiry_submitted
Pushed to dataLayer on the /thank-you/ page:

```javascript
window.dataLayer = window.dataLayer || [];
window.dataLayer.push({
  'event': 'inquiry_submitted',
  'event_category': 'form',
  'event_label': 'inquiry_form'
});
```

This is already implemented in `templates/page-thank-you.php`.

## Audit Checklist

- [ ] GA4 property created with correct settings
- [ ] IP anonymization confirmed ON
- [ ] Google Signals confirmed OFF
- [ ] Ads personalization confirmed OFF
- [ ] GTM container installed on all pages
- [ ] GA4 config tag firing on all pages
- [ ] inquiry_submitted event firing ONLY on /thank-you/
- [ ] Cookie banner installed and defaulting to decline
- [ ] No Meta Pixel present
- [ ] No TikTok Pixel present
- [ ] No session replay tools present
- [ ] No form-field capture in GTM
- [ ] Consent mode configured
