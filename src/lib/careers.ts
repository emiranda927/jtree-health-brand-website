// Careers application — posts to jtree-form-api /api/careers/apply.
// Fields match jtree-form-api/lib/validate.ts CareerApplicationSchema.
type Cfg = { apiUrl?: string; careersApiUrl?: string; thankYouUrl?: string; turnstileSiteKey?: string };
const w = window as unknown as { JTREE_CONFIG?: Cfg; turnstile?: any };

const CONFIG: Cfg = w.JTREE_CONFIG || {};
const API_URL =
  CONFIG.careersApiUrl ||
  (CONFIG.apiUrl || 'https://api.jtreehealth.com/api/inquiry').replace(/\/api\/inquiry$/, '/api/careers/apply');
const THANK_URL = CONFIG.thankYouUrl || '/thank-you/';
const TURNSTILE_KEY = CONFIG.turnstileSiteKey || '';

const form = document.getElementById('careers-form') as HTMLFormElement | null;
if (form) init(form);

function init(form: HTMLFormElement) {
  const submit = form.querySelector('button[type="submit"]') as HTMLButtonElement | null;
  const banner = form.querySelector('.form-error-banner') as HTMLElement | null;
  const originalLabel = submit ? submit.innerHTML : '';

  let widgetId: string | null = null;
  function renderTurnstile() {
    if (!TURNSTILE_KEY) return;
    const c = document.getElementById('cf-turnstile');
    if (!c) return;
    if (!w.turnstile || typeof w.turnstile.render !== 'function') { setTimeout(renderTurnstile, 100); return; }
    try { widgetId = w.turnstile.render(c, { sitekey: TURNSTILE_KEY, theme: 'light', action: 'careers' }); } catch {}
  }
  renderTurnstile();

  const showError = (field: string, msg: string) => {
    const input = form.querySelector('[name="' + field + '"]');
    if (input) input.setAttribute('aria-invalid', 'true');
    const err = form.querySelector('#err-' + field);
    if (err) err.textContent = msg;
  };
  const clearErrors = () => {
    form.querySelectorAll('[aria-invalid]').forEach((el) => el.removeAttribute('aria-invalid'));
    form.querySelectorAll('.jth-field-error').forEach((el) => { el.textContent = ''; });
    if (banner) { banner.textContent = ''; banner.classList.remove('is-visible'); }
  };
  const showBanner = (msg: string) => { if (banner) { banner.textContent = msg; banner.classList.add('is-visible'); } };
  const setBusy = (busy: boolean) => {
    if (!submit) return;
    submit.disabled = busy;
    submit.innerHTML = busy ? 'Sending…' : originalLabel;
    submit.classList.toggle('is-loading', busy);
    if (busy) submit.setAttribute('aria-busy', 'true'); else submit.removeAttribute('aria-busy');
  };

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    clearErrors();
    const data = Object.fromEntries(new FormData(form)) as Record<string, string>;
    if (data.hp_field) { window.location.href = THANK_URL; return; }

    const errors: [string, string][] = [];
    if (!data.applicant_first_name?.trim()) errors.push(['applicant_first_name', 'Please enter your first name.']);
    if (!data.applicant_last_name?.trim()) errors.push(['applicant_last_name', 'Please enter your last name.']);
    if (!data.applicant_email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.applicant_email)) errors.push(['applicant_email', 'Please enter a valid email.']);
    if (!data.applicant_phone || !/^\d{10,11}$/.test(data.applicant_phone.replace(/\D/g, ''))) errors.push(['applicant_phone', 'Please enter a 10-digit phone number.']);
    if (!data.role_interest?.trim()) errors.push(['role_interest', 'Tell us which role interests you.']);
    if (data.resume_url && !/^https?:\/\/.+/.test(data.resume_url.trim())) errors.push(['resume_url', 'Resume link must be a full URL (https://…).']);
    if (!data.consent_contact) errors.push(['consent_contact', 'We need your consent to contact you.']);
    if (errors.length) { errors.forEach((er) => showError(er[0], er[1])); showBanner('Please fix the highlighted fields and try again.'); return; }

    const payload: Record<string, unknown> = {
      applicant_first_name: data.applicant_first_name.trim(),
      applicant_last_name: data.applicant_last_name.trim(),
      applicant_email: data.applicant_email.trim(),
      applicant_phone: data.applicant_phone.trim(),
      role_interest: data.role_interest.trim(),
      consent_contact: data.consent_contact === 'true' || data.consent_contact === 'on',
    };
    if (data.message?.trim()) payload.message = data.message.trim();
    if (data.resume_url?.trim()) payload.resume_url = data.resume_url.trim();

    if (TURNSTILE_KEY && w.turnstile && widgetId !== null) {
      const token = w.turnstile.getResponse(widgetId);
      if (!token) { showBanner('Please complete the verification challenge below the form.'); return; }
      payload.cf_turnstile_response = token;
    }

    setBusy(true);
    try {
      const res = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
        body: JSON.stringify(payload),
      });
      if (res.ok) { window.location.href = THANK_URL; return; }
      let body: any = null;
      try { body = await res.json(); } catch {}
      if (res.status === 429) showBanner('Too many submissions. Please wait a moment and try again.');
      else if (res.status === 403) showBanner('Verification failed. Please retry the challenge below the form.');
      else if (res.status >= 400 && res.status < 500 && body && Array.isArray(body.errors)) {
        body.errors.forEach((er: any) => { if (er && er.field) showError(er.field, er.message || 'Please check this field.'); });
        showBanner('Please fix the highlighted fields and try again.');
      } else showBanner('Something went wrong on our side. Please email admissions@jtreehealth.com and we’ll take it from there.');
      if (TURNSTILE_KEY && w.turnstile && widgetId !== null) { try { w.turnstile.reset(widgetId); } catch {} }
    } catch {
      showBanner('We couldn’t reach our server. Please email admissions@jtreehealth.com, or try again in a minute.');
    } finally {
      setBusy(false);
    }
  });
}
