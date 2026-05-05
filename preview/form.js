// JTree Health · admissions form
// In production this POSTs to https://api.jtreehealth.com/api/inquiry
// In the UI kit we mock the round-trip and redirect to thank-you.html
(function () {
  const form = document.getElementById('inquiry-form');
  if (!form) return;
  const submit = form.querySelector('button[type="submit"]');
  const banner = form.querySelector('.form-error-banner');

  function showError(field, msg) {
    const input = form.querySelector(`[name="${field}"]`);
    if (!input) return;
    input.setAttribute('aria-invalid', 'true');
    const errEl = form.querySelector(`#err-${field}`);
    if (errEl) errEl.textContent = msg;
  }
  function clearErrors() {
    form.querySelectorAll('[aria-invalid]').forEach(el => el.removeAttribute('aria-invalid'));
    form.querySelectorAll('.jth-field-error').forEach(el => el.textContent = '');
    if (banner) banner.classList.remove('is-visible');
  }

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    clearErrors();
    const data = Object.fromEntries(new FormData(form));
    // Honeypot — silent success
    if (data.hp_field) { window.location.href = 'thank-you.html'; return; }
    // Validate
    const errors = [];
    if (!data.parent_first_name?.trim()) errors.push(['parent_first_name', 'Please enter your first name.']);
    if (!data.parent_last_name?.trim())  errors.push(['parent_last_name', 'Please enter your last name.']);
    if (!data.parent_email?.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) errors.push(['parent_email', 'Please enter a valid email.']);
    if (!data.parent_phone?.replace(/\D/g, '').match(/^\d{10,11}$/)) errors.push(['parent_phone', 'Please enter a 10-digit phone number.']);
    if (!data.teen_age) errors.push(['teen_age', 'Please select your teen\u2019s age.']);
    if (!data.program_interest) errors.push(['program_interest', 'Please pick a program.']);
    if (!data.best_time_to_call) errors.push(['best_time_to_call', 'Please pick a time of day.']);
    if (!data.consent_contact) errors.push(['consent_contact', 'We need your consent to contact you.']);
    if (errors.length) {
      errors.forEach(([f, m]) => showError(f, m));
      if (banner) {
        banner.textContent = 'Please fix the highlighted fields and try again.';
        banner.classList.add('is-visible');
      }
      return;
    }
    // Mock the request
    submit.disabled = true; submit.textContent = 'Sending\u2026';
    await new Promise(r => setTimeout(r, 800));
    window.location.href = 'thank-you.html';
  });
})();
