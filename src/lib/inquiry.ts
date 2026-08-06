// Admissions inquiry form — ported from jtree-wp-theme/assets/js/form.js.
// Submits to the live jtree-form-api (api.jtreehealth.com) only after the
// visitor explicitly submits the form. Abandoned form contents are not sent.
//
// API contract (jtree-form-api/lib/validate.ts InquirySchema): a single `name`
// field plus `email`/`phone`, matching the rendered input names directly.
// Config comes from window.JTREE_CONFIG.

type Cfg = { apiUrl?: string; thankYouUrl?: string; turnstileSiteKey?: string };
const w = window as unknown as { JTREE_CONFIG?: Cfg; turnstile?: any; crypto?: Crypto };

const CONFIG: Cfg = w.JTREE_CONFIG || {};
const API_URL = CONFIG.apiUrl || 'https://api.jtreehealth.com/api/inquiry';
const THANK_URL = CONFIG.thankYouUrl || '/thank-you/';
const TURNSTILE_KEY = CONFIG.turnstileSiteKey || '';
const SESSION_KEY = 'jth_session_id';
const UTM_KEY = 'jth_utm';

// Fired when the short form becomes visible, so the Turnstile widget is only
// rendered into a laid-out container. Rendering into a display:none panel gives
// a zero-size or missing widget.
const QUICK_SHOWN = 'jth:quick-inquiry-shown';

const chooser = document.querySelector('[data-admissions]') as HTMLElement | null;
if (chooser) initChooser(chooser);

const form = document.getElementById('inquiry-form') as HTMLFormElement | null;
if (form) init(form);

/**
 * Full intake (the Ritten form) against the short inquiry. Full intake is the
 * default panel; the tabs only swap panels, and the Ritten form opens from its
 * own button rather than on tab click.
 */
function initChooser(root: HTMLElement) {
  const tabs = Array.from(root.querySelectorAll<HTMLButtonElement>('.chooser__tab'));
  const panels = Array.from(root.querySelectorAll<HTMLElement>('[data-panel]'));
  const showTab = (name: string) => {
    tabs.forEach((t) => {
      const on = t.dataset.tab === name;
      t.classList.toggle('is-active', on);
      t.setAttribute('aria-selected', on ? 'true' : 'false');
    });
    panels.forEach((p) => p.classList.toggle('is-hidden', p.dataset.panel !== name));
    if (name === 'quick') document.dispatchEvent(new CustomEvent(QUICK_SHOWN));
  };
  tabs.forEach((t) => t.addEventListener('click', () => showTab(t.dataset.tab || 'full')));
  root.querySelectorAll<HTMLAnchorElement>('[data-goto]').forEach((link) => {
    link.addEventListener('click', (e) => { e.preventDefault(); showTab(link.dataset.goto || 'quick'); });
  });
}

function init(form: HTMLFormElement) {
  const submit = form.querySelector('button[type="submit"]') as HTMLButtonElement | null;
  const banner = form.querySelector('.form-error-banner') as HTMLElement | null;
  const originalLabel = submit ? submit.innerHTML : '';

  // Session id — fresh per tab; matches the partial-capture window.
  function newSessionId() {
    if (w.crypto && w.crypto.randomUUID) return w.crypto.randomUUID().replace(/-/g, '');
    return 'sid_' + Math.random().toString(36).slice(2) + Date.now().toString(36);
  }
  let sessionId: string;
  try {
    sessionId = sessionStorage.getItem(SESSION_KEY) || newSessionId();
    sessionStorage.setItem(SESSION_KEY, sessionId);
  } catch { sessionId = newSessionId(); }
  const sessionInput = document.getElementById('session_id') as HTMLInputElement | null;
  if (sessionInput) sessionInput.value = sessionId;

  // Turnstile widget (only when a site key is configured).
  let widgetId: string | null = null;
  function renderTurnstile() {
    if (!TURNSTILE_KEY) return;
    const container = document.getElementById('cf-turnstile');
    if (!container) return;
    if (!w.turnstile || typeof w.turnstile.render !== 'function') { setTimeout(renderTurnstile, 100); return; }
    try { widgetId = w.turnstile.render(container, { sitekey: TURNSTILE_KEY, theme: 'light', action: 'inquiry' }); }
    catch { /* render failed — submit will surface it */ }
  }
  // Render once, and only once the form is actually on screen. The form starts
  // behind the "Quick inquiry" tab, so on first load that means waiting for the
  // reveal; if the chooser is absent the form is visible immediately.
  let turnstileRendered = false;
  const ensureTurnstile = () => {
    if (turnstileRendered) return;
    turnstileRendered = true;
    renderTurnstile();
  };
  if (form.offsetParent !== null) ensureTurnstile();
  document.addEventListener(QUICK_SHOWN, ensureTurnstile);

  // UTM capture — sticky across navigations within the tab.
  function readUtms(): Record<string, string> {
    const params = new URLSearchParams(window.location.search);
    const picked: Record<string, string> = {};
    ['utm_source', 'utm_medium', 'utm_campaign'].forEach((k) => {
      const v = params.get(k); if (v) picked[k] = v.slice(0, 100);
    });
    if (Object.keys(picked).length) return picked;
    try { return JSON.parse(sessionStorage.getItem(UTM_KEY) || '{}'); } catch { return {}; }
  }
  const utms = readUtms();
  try { sessionStorage.setItem(UTM_KEY, JSON.stringify(utms)); } catch {}

  // Referral fields appear only for referral-shaped sources. A parent who found
  // us on Google is never shown them, so no one has to type "N/A" into a field
  // that does not apply. Values are cleared on hide so a changed answer cannot
  // leave stale provider details in the payload.
  const REFERRAL_SOURCES = ['Referral', 'Doctor', 'School'];
  const referralBlock = form.querySelector<HTMLElement>('[data-referral-fields]');
  const sourceSelect = form.querySelector<HTMLSelectElement>('[name="how_did_you_hear"]');
  const referralShown = () => !!referralBlock && !referralBlock.hidden;
  const syncReferralFields = () => {
    if (!referralBlock || !sourceSelect) return;
    const show = REFERRAL_SOURCES.indexOf(sourceSelect.value) !== -1;
    referralBlock.hidden = !show;
    if (!show) {
      referralBlock.querySelectorAll('input').forEach((el) => { el.value = ''; });
    }
  };
  sourceSelect?.addEventListener('change', syncReferralFields);
  syncReferralFields();
  // Preserve attribution without carrying query strings or fragments that may
  // contain names, search terms, or other sensitive information.
  const referrer = (() => {
    if (!document.referrer) return '';
    try {
      const url = new URL(document.referrer);
      url.search = '';
      url.hash = '';
      return url.toString().slice(0, 500);
    } catch {
      return '';
    }
  })();

  // UI helpers
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

  // Full submission
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    clearErrors();
    const data = Object.fromEntries(new FormData(form)) as Record<string, string>;

    if (data.hp_field) { window.location.href = THANK_URL; return; } // honeypot

    const errors: [string, string][] = [];
    if (!data.name?.trim()) errors.push(['name', 'Please enter your name.']);
    if (!data.client_name?.trim()) errors.push(['client_name', 'Please enter your teen’s name.']);
    if (!data.email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.email)) errors.push(['email', 'Please enter a valid email.']);
    if (!data.phone || !/^\d{10,11}$/.test(data.phone.replace(/\D/g, ''))) errors.push(['phone', 'Please enter a 10-digit phone number.']);
    if (!data.teen_age) errors.push(['teen_age', 'Please select your teen’s age.']);
    if (!data.program_interest) errors.push(['program_interest', 'Please pick a program.']);
    // Every referral field stays optional. "Referral" covers a friend or a
    // relative as readily as a clinician, so someone can legitimately pick it
    // and have no provider to name. Nothing here blocks reaching us.
    if (errors.length) { errors.forEach((er) => showError(er[0], er[1])); showBanner('Please fix the highlighted fields and try again.'); return; }

    const payload: Record<string, unknown> = {
      name: data.name.trim(),
      client_name: data.client_name.trim(),
      lead_type: 'self',
      email: data.email.trim(),
      phone: data.phone.trim(),
      teen_age: parseInt(data.teen_age, 10),
      program_interest: data.program_interest,
      session_id: sessionId,
    };
    if (data.best_time_to_call) payload.best_time_to_call = data.best_time_to_call;
    if (data.how_did_you_hear) payload.how_did_you_hear = data.how_did_you_hear;
    if (data.zip?.trim()) payload.zip = data.zip.trim();
    if (data.insurance) payload.insurance = data.insurance;
    if (data.notes?.trim()) payload.notes = data.notes.trim();
    if (referralShown()) {
      if (data.referral_provider_name?.trim()) payload.referral_provider_name = data.referral_provider_name.trim();
      if (data.referral_organization?.trim()) payload.referral_organization = data.referral_organization.trim();
      if (data.referral_provider_contact?.trim()) payload.referral_provider_contact = data.referral_provider_contact.trim();
    }
    // Attribution — carried through so channel performance / CAC is measurable on completed leads.
    if (utms.utm_source) payload.utm_source = utms.utm_source;
    if (utms.utm_medium) payload.utm_medium = utms.utm_medium;
    if (utms.utm_campaign) payload.utm_campaign = utms.utm_campaign;
    if (referrer) payload.referrer = referrer;

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
      if (res.ok) {
        try { sessionStorage.setItem('jth_inquiry_done', '1'); } catch {}
        window.location.href = THANK_URL;
        return;
      }
      let body: any = null;
      try { body = await res.json(); } catch {}
      // The API reports validation problems as a single {field, message}; its
      // field names now match the rendered input names directly.
      const showApiFieldError = (field: string, message?: string) => {
        showError(field, message || 'Please check this field.');
      };
      if (res.status === 429) showBanner('Too many submissions. Please wait a moment and try again.');
      else if (res.status === 403) showBanner('Verification failed. Please retry the challenge below the form.');
      else if (res.status >= 400 && res.status < 500 && body && Array.isArray(body.errors)) {
        body.errors.forEach((er: any) => { if (er && er.field) showApiFieldError(er.field, er.message); });
        showBanner('Please fix the highlighted fields and try again.');
      } else if (res.status >= 400 && res.status < 500 && body && typeof body.field === 'string') {
        showApiFieldError(body.field, body.message);
        showBanner('Please fix the highlighted fields and try again.');
      } else showBanner('Something went wrong on our side. Please call us at (919) 335-5053 and we’ll take it from there.');
      if (TURNSTILE_KEY && w.turnstile && widgetId !== null) { try { w.turnstile.reset(widgetId); } catch {} }
    } catch {
      showBanner('We couldn’t reach our server. Please call us at (919) 335-5053, or try again in a minute.');
    } finally {
      setBusy(false);
    }
  });
}
