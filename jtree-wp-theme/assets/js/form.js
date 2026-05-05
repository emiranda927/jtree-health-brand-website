// JTree Health · admissions form
// POSTs to https://api.jtreehealth.com/api/inquiry, then redirects to /thank-you/.
// Field names match the API's Zod schema exactly — see jtree-form-api/lib/validate.ts.
(function () {
  var CONFIG    = window.JTREE_CONFIG || {};
  var API_URL   = CONFIG.apiUrl       || 'https://api.jtreehealth.com/api/inquiry';
  var THANK_URL = CONFIG.thankYouUrl  || '/thank-you/';

  var form = document.getElementById('inquiry-form');
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
    submit.textContent = busy ? 'Sending…' : 'Start the Conversation';
  }

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

    // Build a payload that matches the API schema (teen_age int, consent bool)
    var payload = {
      parent_first_name: data.parent_first_name.trim(),
      parent_last_name:  data.parent_last_name.trim(),
      parent_email:      data.parent_email.trim(),
      parent_phone:      data.parent_phone.trim(),
      teen_age:          parseInt(data.teen_age, 10),
      program_interest:  data.program_interest,
      best_time_to_call: data.best_time_to_call,
      consent_contact:   data.consent_contact === 'true' || data.consent_contact === 'on' || data.consent_contact === true,
    };
    if (data.how_did_you_hear) payload.how_did_you_hear = data.how_did_you_hear;

    setBusy(true);

    try {
      var res = await fetch(API_URL, {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body:    JSON.stringify(payload),
      });

      if (res.ok) {
        window.location.href = THANK_URL;
        return;
      }

      var body = null;
      try { body = await res.json(); } catch (_) { /* non-JSON */ }

      if (res.status === 429) {
        showBanner('Too many submissions. Please wait a moment and try again.');
      } else if (res.status >= 400 && res.status < 500 && body && Array.isArray(body.errors)) {
        body.errors.forEach(function (e) { if (e && e.field) showError(e.field, e.message || 'Please check this field.'); });
        showBanner('Please fix the highlighted fields and try again.');
      } else {
        showBanner('Something went wrong on our side. Please call us at (919) 276-4005 and we’ll take it from there.');
      }
    } catch (err) {
      showBanner('We couldn’t reach our server. Please call us at (919) 276-4005, or try again in a minute.');
    } finally {
      setBusy(false);
    }
  });
})();
