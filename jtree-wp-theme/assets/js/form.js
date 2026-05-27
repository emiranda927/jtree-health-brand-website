// JTree Health · admissions form
// Submits to https://api.jtreehealth.com/api/inquiry on full submit, then
// redirects to /thank-you/. On page-leave, if the parent interacted with the
// form but didn't submit, sends a no-PII "partial" beacon to
// /api/inquiry/partial so admissions sees the interest signal without the
// parent's identity. Field names match jtree-form-api/lib/validate.ts.
(function () {
  var CONFIG          = window.JTREE_CONFIG || {};
  var API_URL         = CONFIG.apiUrl          || 'https://api.jtreehealth.com/api/inquiry';
  var PARTIAL_URL     = CONFIG.partialApiUrl   || (API_URL.replace(/\/$/, '') + '/partial');
  var THANK_URL       = CONFIG.thankYouUrl     || '/thank-you/';
  var TURNSTILE_KEY   = CONFIG.turnstileSiteKey || '';
  var SESSION_KEY     = 'jth_session_id';
  var UTM_KEY         = 'jth_utm';

  var form = document.getElementById('inquiry-form');
  if (!form) return;

  var submit = form.querySelector('button[type="submit"]');
  var banner = form.querySelector('.form-error-banner');

  // ── Session + UTM bootstrap ──────────────────────────────────────
  // Fresh session_id per tab. sessionStorage is cleared on tab close, which
  // matches the partial-capture window (one visit, one chance to follow up).
  function newSessionId() {
    if (window.crypto && window.crypto.randomUUID) {
      return window.crypto.randomUUID().replace(/-/g, '');
    }
    return 'sid_' + Math.random().toString(36).slice(2) + Date.now().toString(36);
  }
  var sessionId;
  try {
    sessionId = sessionStorage.getItem(SESSION_KEY) || newSessionId();
    sessionStorage.setItem(SESSION_KEY, sessionId);
  } catch (_) {
    sessionId = newSessionId();
  }
  var sessionInput = document.getElementById('session_id');
  if (sessionInput) sessionInput.value = sessionId;

  // ── Turnstile widget ─────────────────────────────────────────────
  // Renders only when a site key is configured. Without a key, the API
  // verifier falls open and we never load the Cloudflare script — keeps
  // dev/staging fast and silent. `turnstile.ready` polls internally until
  // the global is ready, so we don't have to.
  var turnstileWidgetId = null;
  function tryRenderTurnstile() {
    if (!TURNSTILE_KEY) return;
    var container = document.getElementById('cf-turnstile');
    if (!container) return;
    if (!window.turnstile || typeof window.turnstile.render !== 'function') {
      // Script may not have parsed yet — retry on next tick.
      window.setTimeout(tryRenderTurnstile, 100);
      return;
    }
    try {
      turnstileWidgetId = window.turnstile.render(container, {
        sitekey: TURNSTILE_KEY,
        theme:   'light',
        action:  'inquiry',
      });
    } catch (_) { /* render failed — submit will fail without a token */ }
  }
  tryRenderTurnstile();

  // UTM params: prefer the URL we landed on; fall back to a stash from any
  // previous page in this session. Sticky across navigations within the tab.
  function readUtms() {
    var params = new URLSearchParams(window.location.search);
    var picked = {};
    ['utm_source','utm_medium','utm_campaign'].forEach(function (k) {
      var v = params.get(k);
      if (v) picked[k] = v.slice(0, 100);
    });
    if (Object.keys(picked).length) return picked;
    try { return JSON.parse(sessionStorage.getItem(UTM_KEY) || '{}'); }
    catch (_) { return {}; }
  }
  var utms = readUtms();
  try { sessionStorage.setItem(UTM_KEY, JSON.stringify(utms)); } catch (_) {}
  var referrer = (document.referrer || '').slice(0, 500);

  // ── Validation + UI helpers ──────────────────────────────────────
  function showError(field, msg) {
    var input = form.querySelector('[name="' + field + '"]');
    if (input) input.setAttribute('aria-invalid', 'true');
    var err = form.querySelector('#err-' + field);
    if (err) err.textContent = msg;
  }
  function clearErrors() {
    form.querySelectorAll('[aria-invalid]').forEach(function (el) { el.removeAttribute('aria-invalid'); });
    form.querySelectorAll('.jth-field-error').forEach(function (el) { el.textContent = ''; });
    if (banner) { banner.textContent = ''; banner.classList.remove('is-visible'); }
  }
  function showBanner(msg) {
    if (!banner) return;
    banner.textContent = msg;
    banner.classList.add('is-visible');
  }
  function setBusy(busy) {
    submit.disabled = busy;
    submit.textContent = busy ? 'Sending…' : 'Start the Conversation';
    // Visual + a11y: shimmer skeleton on the button surface, plus aria-busy
    // so screen readers announce the loading state without us inventing
    // a separate live region.
    submit.classList.toggle('is-loading', busy);
    if (busy) submit.setAttribute('aria-busy', 'true'); else submit.removeAttribute('aria-busy');
  }

  // ── Partial-capture state ────────────────────────────────────────
  // We fire ONCE per session, only if the parent actually interacted with a
  // non-identifying field. Submitting (even with an error) suppresses it —
  // a successful submit makes the partial redundant, and a server-rejected
  // submit means the parent is still here and may retry.
  var interacted   = false;
  var fullSubmitted = false;
  var partialSent  = false;

  // Only these fields trigger a partial — text PII fields (name/email/phone)
  // intentionally do NOT mark the user as "interacted" for this purpose.
  var PARTIAL_TRIGGERS = ['teen_age', 'program_interest', 'best_time_to_call', 'how_did_you_hear'];
  PARTIAL_TRIGGERS.forEach(function (name) {
    var el = form.querySelector('[name="' + name + '"]');
    if (!el) return;
    el.addEventListener('change', function () { interacted = true; });
  });

  function buildPartialPayload() {
    var data = Object.fromEntries(new FormData(form));
    var payload = { session_id: sessionId };
    PARTIAL_TRIGGERS.forEach(function (name) {
      if (data[name]) payload[name] = data[name];
    });
    if (utms.utm_source)   payload.utm_source   = utms.utm_source;
    if (utms.utm_medium)   payload.utm_medium   = utms.utm_medium;
    if (utms.utm_campaign) payload.utm_campaign = utms.utm_campaign;
    if (referrer)          payload.referrer     = referrer;
    return payload;
  }

  function sendPartialIfApplicable() {
    if (partialSent || fullSubmitted || !interacted) return;
    partialSent = true;
    var payload = buildPartialPayload();
    var body = JSON.stringify(payload);
    try {
      if (navigator.sendBeacon) {
        var blob = new Blob([body], { type: 'application/json' });
        navigator.sendBeacon(PARTIAL_URL, blob);
        return;
      }
    } catch (_) { /* fall through to fetch */ }
    // Fallback: keepalive fetch. Best-effort — no await, no error UI.
    try {
      fetch(PARTIAL_URL, {
        method:    'POST',
        headers:   { 'Content-Type': 'application/json' },
        body:      body,
        keepalive: true,
      });
    } catch (_) { /* swallow — page is closing */ }
  }

  // pagehide is the reliable "leaving" signal in modern browsers (works
  // with bfcache, mobile Safari, etc.). visibilitychange-to-hidden catches
  // tab-switch on mobile where pagehide may not fire.
  window.addEventListener('pagehide', sendPartialIfApplicable);
  document.addEventListener('visibilitychange', function () {
    if (document.visibilityState === 'hidden') sendPartialIfApplicable();
  });

  // ── Full submission ──────────────────────────────────────────────
  form.addEventListener('submit', async function (e) {
    e.preventDefault();
    clearErrors();

    var data = Object.fromEntries(new FormData(form));

    // Honeypot — silent success on bot fill
    if (data.hp_field) {
      window.location.href = THANK_URL;
      return;
    }

    // Client-side validation
    var errors = [];
    if (!data.parent_first_name || !data.parent_first_name.trim())
      errors.push(['parent_first_name', 'Please enter your first name.']);
    if (!data.parent_last_name || !data.parent_last_name.trim())
      errors.push(['parent_last_name', 'Please enter your last name.']);
    if (!data.parent_email || !data.parent_email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/))
      errors.push(['parent_email', 'Please enter a valid email.']);
    if (!data.parent_phone || !data.parent_phone.replace(/\D/g, '').match(/^\d{10,11}$/))
      errors.push(['parent_phone', 'Please enter a 10-digit phone number.']);
    if (!data.teen_age) errors.push(['teen_age', 'Please select your teen’s age.']);
    if (!data.program_interest) errors.push(['program_interest', 'Please pick a program.']);
    if (!data.best_time_to_call) errors.push(['best_time_to_call', 'Please pick a time of day.']);
    if (!data.consent_contact) errors.push(['consent_contact', 'We need your consent to contact you.']);

    if (errors.length) {
      errors.forEach(function (e) { showError(e[0], e[1]); });
      showBanner('Please fix the highlighted fields and try again.');
      return;
    }

    var payload = {
      parent_first_name: data.parent_first_name.trim(),
      parent_last_name:  data.parent_last_name.trim(),
      parent_email:      data.parent_email.trim(),
      parent_phone:      data.parent_phone.trim(),
      teen_age:          parseInt(data.teen_age, 10),
      program_interest:  data.program_interest,
      best_time_to_call: data.best_time_to_call,
      consent_contact:   data.consent_contact === 'true' || data.consent_contact === 'on' || data.consent_contact === true,
      session_id:        sessionId,
    };
    if (data.how_did_you_hear) payload.how_did_you_hear = data.how_did_you_hear;

    // Turnstile token, if a widget is mounted. The API verifier falls open
    // when no site key is configured, so an absent token is fine in dev.
    if (TURNSTILE_KEY && window.turnstile && turnstileWidgetId !== null) {
      var token = window.turnstile.getResponse(turnstileWidgetId);
      if (!token) {
        showBanner('Please complete the verification challenge below the form.');
        return;
      }
      payload.cf_turnstile_response = token;
    }

    setBusy(true);

    try {
      var res = await fetch(API_URL, {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body:    JSON.stringify(payload),
      });

      if (res.ok) {
        // Block the pagehide partial — the full submission supersedes it.
        fullSubmitted = true;
        window.location.href = THANK_URL;
        return;
      }

      var body = null;
      try { body = await res.json(); } catch (_) { /* non-JSON */ }

      if (res.status === 429) {
        showBanner('Too many submissions. Please wait a moment and try again.');
      } else if (res.status === 403) {
        showBanner('Verification failed. Please retry the challenge below the form.');
      } else if (res.status >= 400 && res.status < 500 && body && Array.isArray(body.errors)) {
        body.errors.forEach(function (e) { if (e && e.field) showError(e.field, e.message || 'Please check this field.'); });
        showBanner('Please fix the highlighted fields and try again.');
      } else {
        showBanner('Something went wrong on our side. Please call us at (919) 335-5053 and we’ll take it from there.');
      }
      // Reset Turnstile so the parent can retry without re-loading the page.
      if (TURNSTILE_KEY && window.turnstile && turnstileWidgetId !== null) {
        try { window.turnstile.reset(turnstileWidgetId); } catch (_) {}
      }
    } catch (err) {
      showBanner('We couldn’t reach our server. Please call us at (919) 335-5053, or try again in a minute.');
    } finally {
      setBusy(false);
    }
  });
})();
