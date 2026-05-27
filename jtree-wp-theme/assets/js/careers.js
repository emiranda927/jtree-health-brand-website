// JTree Health · careers application form
// POSTs to /api/careers/apply, then swaps the form for a thank-you state.
// Field names match jtree-form-api/lib/validate.ts CareerApplicationSchema.
(function () {
  var CONFIG       = window.JTREE_CONFIG || {};
  var INQUIRY_URL  = CONFIG.apiUrl || 'https://api.jtreehealth.com/api/inquiry';
  // /api/inquiry → /api/careers/apply (sibling endpoint on same origin)
  var CAREERS_URL  = CONFIG.careersApiUrl ||
    INQUIRY_URL.replace(/\/api\/inquiry\/?$/, '/api/careers/apply');
  var TURNSTILE_KEY = CONFIG.turnstileSiteKey || '';

  var form = document.getElementById('careers-form');
  if (!form) return;

  var submit = form.querySelector('button[type="submit"]');
  var banner = form.querySelector('.form-error-banner');

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
    submit.textContent = busy ? 'Sending…' : 'Send application';
    submit.classList.toggle('is-loading', busy);
    if (busy) submit.setAttribute('aria-busy', 'true'); else submit.removeAttribute('aria-busy');
  }

  // Turnstile widget — same falls-open contract as the inquiry form.
  var turnstileWidgetId = null;
  function tryRenderTurnstile() {
    if (!TURNSTILE_KEY) return;
    var container = document.getElementById('cf-turnstile-careers');
    if (!container) return;
    if (!window.turnstile || typeof window.turnstile.render !== 'function') {
      window.setTimeout(tryRenderTurnstile, 100);
      return;
    }
    try {
      turnstileWidgetId = window.turnstile.render(container, {
        sitekey: TURNSTILE_KEY,
        theme:   'light',
        action:  'careers',
      });
    } catch (_) {}
  }
  tryRenderTurnstile();

  form.addEventListener('submit', async function (e) {
    e.preventDefault();
    clearErrors();

    var data = Object.fromEntries(new FormData(form));

    if (data.hp_field) {
      submit.textContent = 'Sent';
      return;
    }

    var errors = [];
    if (!data.applicant_first_name || !data.applicant_first_name.trim())
      errors.push(['applicant_first_name', 'Please enter your first name.']);
    if (!data.applicant_last_name || !data.applicant_last_name.trim())
      errors.push(['applicant_last_name', 'Please enter your last name.']);
    if (!data.applicant_email || !data.applicant_email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/))
      errors.push(['applicant_email', 'Please enter a valid email.']);
    if (!data.applicant_phone || !data.applicant_phone.replace(/\D/g, '').match(/^\d{10,11}$/))
      errors.push(['applicant_phone', 'Please enter a 10-digit phone number.']);
    if (!data.role_interest)
      errors.push(['role_interest', 'Please pick a role.']);
    if (data.resume_url && !data.resume_url.match(/^https?:\/\//))
      errors.push(['resume_url', 'Please paste a full URL starting with http(s)://.']);
    if (!data.consent_contact)
      errors.push(['consent_contact', 'We need your consent to follow up.']);

    if (errors.length) {
      errors.forEach(function (err) { showError(err[0], err[1]); });
      showBanner('Please fix the highlighted fields and try again.');
      return;
    }

    var payload = {
      applicant_first_name: data.applicant_first_name.trim(),
      applicant_last_name:  data.applicant_last_name.trim(),
      applicant_email:      data.applicant_email.trim(),
      applicant_phone:      data.applicant_phone.trim(),
      role_interest:        data.role_interest,
      consent_contact:      data.consent_contact === 'true' || data.consent_contact === 'on' || data.consent_contact === true,
    };
    if (data.resume_url) payload.resume_url = data.resume_url.trim();
    if (data.message)    payload.message    = data.message.trim();

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
      var res = await fetch(CAREERS_URL, {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body:    JSON.stringify(payload),
      });

      if (res.ok) {
        var thankyou = document.getElementById('careers-thankyou');
        if (thankyou) {
          form.style.display = 'none';
          thankyou.hidden = false;
          thankyou.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } else {
          showBanner("Thanks — we'll be in touch.");
          submit.textContent = 'Sent';
        }
        return;
      }

      var body = null;
      try { body = await res.json(); } catch (_) {}

      if (res.status === 429) {
        showBanner('Too many submissions. Please wait a moment and try again.');
      } else if (res.status === 403) {
        showBanner('Verification failed. Please retry the challenge.');
      } else if (res.status === 422 && body && body.field) {
        showError(body.field, body.message || 'Please check this field.');
        showBanner('Please fix the highlighted fields and try again.');
      } else {
        showBanner('Something went wrong. Please email careers@jtreehealth.com directly.');
      }
      if (TURNSTILE_KEY && window.turnstile && turnstileWidgetId !== null) {
        try { window.turnstile.reset(turnstileWidgetId); } catch (_) {}
      }
    } catch (err) {
      showBanner("We couldn't reach our server. Please email careers@jtreehealth.com.");
    } finally {
      setBusy(false);
    }
  });
})();
