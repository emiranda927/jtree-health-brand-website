// Provider referral page — two paths:
//   1. Full clinical referral -> opens the secure Ritten form in a new tab (a
//      plain link in the markup; no JS needed, PHI stays inside Ritten).
//   2. Quick referral -> a short "call me back" form. It reuses the live
//      admissions API (api.jtreehealth.com/api/inquiry) by mapping the provider
//      fields onto the InquirySchema: provider contact -> name/email/phone,
//      patient age -> teen_age, level-of-care -> program_interest, and the
//      referral detail (role, org, initials, timeframe, reason) folded into
//      `notes`. how_did_you_hear is fixed to "Referral" so the team can tell
//      referrals apart. Detailed PHI is deliberately kept off this form.
//
// This script also owns the tab toggle between the two panels.

type Cfg = { apiUrl?: string; turnstileSiteKey?: string };
const w = window as unknown as { JTREE_CONFIG?: Cfg; turnstile?: any; crypto?: Crypto };

const CONFIG: Cfg = w.JTREE_CONFIG || {};
const API_URL = CONFIG.apiUrl || 'https://api.jtreehealth.com/api/inquiry';
const TURNSTILE_KEY = CONFIG.turnstileSiteKey || '';
const SESSION_KEY = 'jth_session_id';
const UTM_KEY = 'jth_utm';

const root = document.querySelector('[data-referral]') as HTMLElement | null;
if (root) init(root);

function init(root: HTMLElement) {
  // ---- Tab switching -------------------------------------------------------
  const tabs = Array.from(root.querySelectorAll<HTMLButtonElement>('.chooser__tab'));
  const panels = Array.from(root.querySelectorAll<HTMLElement>('[data-panel]'));
  const showTab = (name: string) => {
    tabs.forEach((t) => {
      const on = t.dataset.tab === name;
      t.classList.toggle('is-active', on);
      t.setAttribute('aria-selected', on ? 'true' : 'false');
    });
    panels.forEach((p) => p.classList.toggle('is-hidden', p.dataset.panel !== name));
  };
  tabs.forEach((t) => t.addEventListener('click', () => showTab(t.dataset.tab || 'full')));
  root.querySelectorAll<HTMLAnchorElement>('[data-goto]').forEach((link) => {
    link.addEventListener('click', (e) => { e.preventDefault(); showTab(link.dataset.goto || 'quick'); });
  });

  // ---- Quick-referral form -------------------------------------------------
  const form = root.querySelector('#referral-quick-form') as HTMLFormElement | null;
  if (!form) return;
  const success = root.querySelector('[data-referral-success]') as HTMLElement | null;
  const resetLink = root.querySelector('[data-referral-reset]') as HTMLAnchorElement | null;
  const submit = form.querySelector('button[type="submit"]') as HTMLButtonElement | null;
  const banner = form.querySelector('.form-error-banner') as HTMLElement | null;
  const originalLabel = submit ? submit.innerHTML : '';

  // Session id — fresh per tab.
  function newSessionId() {
    if (w.crypto && w.crypto.randomUUID) return w.crypto.randomUUID().replace(/-/g, '');
    return 'sid_' + Math.random().toString(36).slice(2) + Date.now().toString(36);
  }
  let sessionId: string;
  try {
    sessionId = sessionStorage.getItem(SESSION_KEY) || newSessionId();
    sessionStorage.setItem(SESSION_KEY, sessionId);
  } catch { sessionId = newSessionId(); }
  const sessionInput = form.querySelector('#referral_session_id') as HTMLInputElement | null;
  if (sessionInput) sessionInput.value = sessionId;

  // Turnstile (only when a site key is configured).
  let widgetId: string | null = null;
  function renderTurnstile() {
    if (!TURNSTILE_KEY) return;
    const container = document.getElementById('cf-turnstile-referral');
    if (!container) return;
    if (!w.turnstile || typeof w.turnstile.render !== 'function') { setTimeout(renderTurnstile, 100); return; }
    try { widgetId = w.turnstile.render(container, { sitekey: TURNSTILE_KEY, theme: 'light', action: 'referral' }); }
    catch { /* render failed — submit will surface it */ }
  }
  renderTurnstile();

  // UTM + referrer attribution (mirrors inquiry.ts).
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
  const referrer = (() => {
    if (!document.referrer) return '';
    try {
      const url = new URL(document.referrer);
      url.search = ''; url.hash = '';
      return url.toString().slice(0, 500);
    } catch { return ''; }
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

  // Map the design's best-time options onto the API's enum (Morning/Afternoon/Evening).
  const mapBestTime = (v: string): string | undefined => {
    if (v === 'Morning') return 'Morning';
    if (v === 'Afternoon' || v === 'Late afternoon') return 'Afternoon';
    return undefined; // "No preference"
  };
  // Level-of-care -> program_interest enum (IOP/PHP/Unsure).
  const mapProgram = (v: string): 'IOP' | 'PHP' | 'Unsure' => (v === 'IOP' || v === 'PHP' ? v : 'Unsure');

  const buildNotes = (d: Record<string, string>): string => {
    const locLabel = d.level_of_care === 'IOP' || d.level_of_care === 'PHP'
      ? d.level_of_care
      : d.level_of_care === 'Either' ? 'Either — help me decide' : 'Not sure';
    const lines = [
      'Provider referral (via website Quick referral).',
      `Role: ${d.role || '—'}`,
      `Organization: ${d.organization?.trim() || '—'}`,
      `Patient initials: ${d.patient_initials?.trim() || '—'}`,
      `Level of care requested: ${locLabel}`,
      `Timeframe: ${d.timeframe || 'Routine'}`,
      `Reason: ${d.reason?.trim() || '—'}`,
    ];
    return lines.join('\n').slice(0, 1000);
  };

  resetLink?.addEventListener('click', (e) => {
    e.preventDefault();
    form.reset();
    clearErrors();
    if (success) success.classList.add('is-hidden');
    form.classList.remove('is-hidden');
    if (TURNSTILE_KEY && w.turnstile && widgetId !== null) { try { w.turnstile.reset(widgetId); } catch {} }
  });

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    clearErrors();
    const data = Object.fromEntries(new FormData(form)) as Record<string, string>;

    if (data.hp_field) { if (success) { form.classList.add('is-hidden'); success.classList.remove('is-hidden'); } return; }

    const errors: [string, string][] = [];
    if (!data.name?.trim()) errors.push(['name', 'Please enter your name.']);
    if (!data.role) errors.push(['role', 'Please select your role.']);
    if (!data.email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.email)) errors.push(['email', 'Please enter a valid email.']);
    if (!data.phone || !/^\d{10,11}$/.test(data.phone.replace(/\D/g, ''))) errors.push(['phone', 'Please enter a 10-digit phone number.']);
    if (!data.patient_age) errors.push(['patient_age', "Please select the patient's age."]);
    if (errors.length) { errors.forEach((er) => showError(er[0], er[1])); showBanner('Please fix the highlighted fields and try again.'); return; }

    const payload: Record<string, unknown> = {
      name: data.name.trim(),
      email: data.email.trim(),
      phone: data.phone.trim(),
      teen_age: parseInt(data.patient_age, 10),
      program_interest: mapProgram(data.level_of_care),
      how_did_you_hear: 'Referral',
      notes: buildNotes(data),
      session_id: sessionId,
    };
    const bt = mapBestTime(data.best_time);
    if (bt) payload.best_time_to_call = bt;
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
        if (success) { form.classList.add('is-hidden'); success.classList.remove('is-hidden'); }
        return;
      }
      let body: any = null;
      try { body = await res.json(); } catch {}
      if (res.status === 429) showBanner('Too many submissions. Please wait a moment and try again.');
      else if (res.status === 403) showBanner('Verification failed. Please retry the challenge below the form.');
      else if (res.status >= 400 && res.status < 500 && body && Array.isArray(body.errors)) {
        // Map any API field errors we can surface; provider-form field names differ,
        // so unknown fields fall back to the banner.
        const known = new Set(['name', 'email', 'phone']);
        body.errors.forEach((er: any) => { if (er && er.field && known.has(er.field)) showError(er.field, er.message); });
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
