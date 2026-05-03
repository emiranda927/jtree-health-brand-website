<?php
/**
 * Template Name: Admissions
 */
?>
<?php get_header(); ?>

<style>
/* ── ADMISSIONS-SPECIFIC ──────────────────────── */
.admissions-grid {
  max-width: 1280px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 380px 1fr;
  gap: 5rem;
  align-items: start;
}

/* Steps sidebar */
.steps-sidebar {}
.step-item {
  display: flex;
  gap: 1.25rem;
  padding: 1.5rem 0;
  border-bottom: 1px solid rgba(30,61,52,.08);
  position: relative;
}
.step-item:last-of-type { border-bottom: none; }
.step-num {
  width: 40px; height: 40px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-family: var(--font-head);
  font-weight: 800;
  font-size: .9rem;
  flex-shrink: 0;
}
.step-num--1 { background: var(--forest); color: #fff; }
.step-num--2 { background: var(--deep-sage); color: #fff; }
.step-num--3 { background: var(--mist); color: #fff; }
.step-title { font-family: var(--font-head); font-weight: 700; font-size: 1rem; color: var(--forest); margin-bottom: .3rem; }
.step-desc  { font-size: .85rem; color: rgba(42,42,42,.6); line-height: 1.6; }

/* Step illustrations */
.step-illus {
  position: absolute;
  right: -8px;
  top: 50%;
  transform: translateY(-50%);
  opacity: 0.18;
  pointer-events: none;
}

.contact-card {
  background: var(--sand);
  border: 1.5px solid rgba(30,61,52,.1);
  border-radius: 16px;
  padding: 1.5rem;
  margin-top: 1.5rem;
}
.contact-card__label { font-size: .62rem; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: rgba(42,42,42,.4); margin-bottom: .5rem; }
.contact-card__phone { font-family: var(--font-head); font-weight: 800; font-size: 1.6rem; color: var(--forest); letter-spacing: -.02em; }
.contact-card__hours { font-size: .78rem; color: rgba(42,42,42,.45); margin-top: .25rem; }

.privacy-card {
  background: var(--pale-sage);
  border: 1px solid rgba(30,61,52,.08);
  border-radius: 12px;
  padding: 1.1rem 1.25rem;
  margin-top: 1rem;
  font-size: .78rem;
  color: rgba(42,42,42,.55);
  line-height: 1.6;
  position: relative;
  overflow: hidden;
}
.privacy-card strong { color: var(--forest); }

/* Form card */
.form-card {
  background: white;
  border-radius: 24px;
  border: 1.5px solid rgba(30,61,52,.08);
  padding: 2.5rem;
  box-shadow: 0 8px 40px rgba(30,61,52,.06);
  position: relative;
  overflow: hidden;
}
.form-card__title { font-family: var(--font-head); font-weight: 800; font-size: 1.6rem; color: var(--forest); margin-bottom: .35rem; }
.form-card__sub   { font-size: .85rem; color: rgba(42,42,42,.45); margin-bottom: 2rem; line-height: 1.5; }

/* Form fields */
.form-row  { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.form-group { margin-bottom: 1.1rem; }
.form-label {
  display: block;
  font-size: .68rem;
  font-weight: 700;
  letter-spacing: .12em;
  text-transform: uppercase;
  color: var(--forest);
  margin-bottom: .5rem;
}
.form-label .req { color: #c0392b; }
.form-input {
  width: 100%;
  border: 2px solid rgba(30,61,52,.12);
  border-radius: 8px;
  padding: .75rem 1rem;
  font-size: .9rem;
  font-family: var(--font-body);
  background: var(--sand);
  color: var(--charcoal);
  transition: border-color .2s, background .2s;
  outline: none;
  appearance: none;
  -webkit-appearance: none;
}
.form-input:focus { border-color: var(--deep-sage); background: #fff; }
.form-input.is-error { border-color: #c0392b; background: #fff8f8; }
.form-error { font-size: .75rem; color: #c0392b; margin-top: .3rem; display: none; }
.form-error.visible { display: block; }

/* Consent row */
.consent-row {
  display: flex;
  align-items: flex-start;
  gap: .75rem;
  padding: 1.25rem;
  background: var(--pale-sage);
  border-radius: 10px;
  border: 1.5px solid rgba(30,61,52,.08);
  margin-bottom: 1.25rem;
  cursor: pointer;
}
.consent-row input[type="checkbox"] {
  width: 18px; height: 18px;
  margin-top: .1rem;
  accent-color: var(--forest);
  flex-shrink: 0;
  cursor: pointer;
}
.consent-row label { font-size: .88rem; color: rgba(42,42,42,.7); line-height: 1.55; cursor: pointer; }

.submit-btn {
  width: 100%;
  background: var(--forest);
  color: #fff;
  border: none;
  border-radius: 10px;
  padding: 1rem 2rem;
  font-family: var(--font-body);
  font-weight: 700;
  font-size: 1rem;
  cursor: pointer;
  transition: background .2s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: .5rem;
}
.submit-btn:hover:not(:disabled) { background: var(--deep-sage); }
.submit-btn:disabled { opacity: .65; cursor: not-allowed; }
.submit-btn .loading { display: none; }

#form-alert {
  display: none;
  padding: 1rem 1.25rem;
  border-radius: 10px;
  font-size: .88rem;
  font-weight: 600;
  margin-bottom: 1.25rem;
  line-height: 1.5;
}
#form-alert.error   { background: #fff0f0; color: #c0392b; border: 1.5px solid #f5c6c6; }
#form-alert.success { background: #f0fff4; color: #276749; border: 1.5px solid #b7e1c8; }

/* Insurance strip */
.ins-strip {
  max-width: 1280px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  gap: 2.5rem;
  flex-wrap: wrap;
}
.ins-strip__label { font-size: .72rem; font-weight: 700; letter-spacing: .15em; text-transform: uppercase; color: rgba(42,42,42,.4); }
.ins-strip__logos { display: flex; gap: 1.5rem; flex-wrap: wrap; }
.ins-strip__logo  { background: rgba(255,255,255,.6); border: 1.5px solid rgba(30,61,52,.1); padding: .5rem 1rem; border-radius: 8px; font-family: var(--font-head); font-weight: 700; font-size: .85rem; color: rgba(42,42,42,.5); }

@media (max-width: 1024px) {
  .admissions-grid { grid-template-columns: 1fr; }
  .form-row { grid-template-columns: 1fr; }
  .step-illus { display: none; }
}
</style>

<!-- PAGE HERO -->
<div class="spike-bg" style="position:relative;overflow:hidden;">
  <div class="page-hero">
    <p class="page-hero__eyebrow anim-1">No waitlist · We respond within 1 business day · In-network with major insurers</p>
    <h1 class="page-hero__title anim-2">Let's find the right fit for your family.</h1>
    <p class="page-hero__sub anim-3">Fill out the short form below and we'll call you within one business day. No commitment — just a real conversation about what your teen needs.</p>
  </div>

</div>

<!-- Wave divider: hero to form -->
<div class="wave-divider" style="background:var(--sand);">
  <svg viewBox="0 0 1440 60" fill="none" preserveAspectRatio="none" style="height:50px;">
    <path d="M0 30 C240 60 480 0 720 30 C960 60 1200 0 1440 30 L1440 60 L0 60 Z" fill="#EAE8F5"/>
  </svg>
</div>

<!-- FORM SECTION -->
<section class="section section--lav" style="position:relative;overflow:hidden;">
  <div class="admissions-grid">

    <!-- Left: Steps -->
    <div data-reveal>
      <p class="section-label">How It Works</p>
      <h2 class="section-head" style="font-size:1.8rem;margin-bottom:1.5rem;">Three steps to get started.</h2>

      <div class="step-item" data-reveal>
        <div class="step-num step-num--1">1</div>
        <div>
          <p class="step-title">Fill out the form</p>
          <p class="step-desc">Takes 2 minutes. No medical details needed here — we'll discuss everything by phone.</p>
        </div>
        <!-- Clipboard illustration -->
        <div class="step-illus">
          <svg width="36" height="44" viewBox="0 0 36 44" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="4" y="6" width="28" height="36" rx="4" fill="#1E3D34" opacity="0.25"/>
            <rect x="10" y="2" width="16" height="8" rx="3" fill="#2C5F52" opacity="0.35"/>
            <rect x="10" y="18" width="16" height="2.5" rx="1" fill="#B8E04A" opacity="0.5"/>
            <rect x="10" y="24" width="12" height="2.5" rx="1" fill="#B8E04A" opacity="0.35"/>
            <rect x="10" y="30" width="14" height="2.5" rx="1" fill="#B8E04A" opacity="0.25"/>
          </svg>
        </div>
      </div>
      <div class="step-item" data-reveal>
        <div class="step-num step-num--2">2</div>
        <div>
          <p class="step-title">We call you</p>
          <p class="step-desc">A real admissions coordinator reaches out within one business day at your preferred time.</p>
        </div>
        <!-- Phone illustration -->
        <div class="step-illus">
          <svg width="32" height="44" viewBox="0 0 32 44" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="4" y="2" width="24" height="40" rx="6" fill="#2C5F52" opacity="0.25"/>
            <rect x="8" y="8" width="16" height="24" rx="2" fill="#A89FD8" opacity="0.2"/>
            <circle cx="16" cy="38" r="2.5" fill="#B8E04A" opacity="0.4"/>
            <circle cx="16" cy="5" r="1.5" fill="#EAE8F5" opacity="0.4"/>
          </svg>
        </div>
      </div>
      <div class="step-item" data-reveal>
        <div class="step-num step-num--3">3</div>
        <div>
          <p class="step-title">Start within the week</p>
          <p class="step-desc">We talk through your teen's situation and recommend the right level of care. No pressure — and if we're not the right fit, we'll tell you honestly.</p>
        </div>
        <!-- Calendar illustration -->
        <div class="step-illus">
          <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="3" y="6" width="32" height="30" rx="4" fill="#1E3D34" opacity="0.22"/>
            <rect x="3" y="6" width="32" height="10" rx="4" fill="#2C5F52" opacity="0.3"/>
            <line x1="10" y1="2" x2="10" y2="9" stroke="#1E3D34" stroke-width="2.5" stroke-linecap="round" opacity="0.3"/>
            <line x1="28" y1="2" x2="28" y2="9" stroke="#1E3D34" stroke-width="2.5" stroke-linecap="round" opacity="0.3"/>
            <circle cx="12" cy="23" r="2.5" fill="#B8E04A" opacity="0.45"/>
            <circle cx="19" cy="23" r="2.5" fill="#A89FD8" opacity="0.3"/>
            <circle cx="26" cy="23" r="2.5" fill="#E0EEEA" opacity="0.5"/>
            <circle cx="12" cy="31" r="2.5" fill="#E0EEEA" opacity="0.35"/>
            <circle cx="19" cy="31" r="2.5" fill="#B8E04A" opacity="0.3"/>
          </svg>
        </div>
      </div>

      <div class="contact-card" data-reveal>
        <p class="contact-card__label">Prefer to call?</p>
        <a href="tel:9192764005" class="contact-card__phone">(919) 276-4005</a>
        <p class="contact-card__hours">Mon–Fri · 8:00am–6:00pm ET</p>
      </div>

      <div class="privacy-card" data-reveal>
        <!-- Lock/shield illustration -->
        <strong>&#128274; Your privacy matters.</strong><br/>
        We collect only what's needed to reach you. No medical details in this form. Your information is never sold or shared with third parties. <a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>" style="color:var(--deep-sage);text-decoration:underline;">Privacy Policy &rarr;</a>
      </div>
    </div>

    <!-- Right: Form -->
    <div class="form-card" data-reveal="right">
      <!-- Decorative dot pattern (background) -->

      <!-- Small growing plant near bottom-left of form -->

      <h2 class="form-card__title">Request a Callback</h2>
      <p class="form-card__sub">No medical details here — we'll cover everything by phone. This form takes about 2 minutes.</p>

      <div id="form-alert"></div>

      <?php jtree_render_inquiry_form(); ?>
    </div>
  </div>

</section>

<!-- Wave divider: form to insurance -->
<div class="wave-divider" style="background:var(--pale-lav);">
  <svg viewBox="0 0 1440 60" fill="none" preserveAspectRatio="none" style="height:50px;">
    <path d="M0 20 C180 50 360 0 540 25 C720 50 900 5 1080 25 C1260 45 1380 15 1440 30 L1440 60 L0 60 Z" fill="#F5F0E8"/>
  </svg>
</div>

<!-- INSURANCE STRIP -->
<section class="section" style="padding:3rem 2rem;" data-reveal>
  <div class="ins-strip">
    <span class="ins-strip__label">We're in-network with</span>
    <div class="ins-strip__logos">
      <span class="ins-strip__logo">BCBS</span>
      <span class="ins-strip__logo">Cigna</span>
      <span class="ins-strip__logo">Aetna</span>
      <span class="ins-strip__logo">UHC</span>
      <span class="ins-strip__logo">Tricare</span>
    </div>
    <a href="<?php echo esc_url( home_url( '/insurance/' ) ); ?>" style="font-size:.82rem;font-weight:600;color:var(--deep-sage);text-decoration:underline;white-space:nowrap;">Check your coverage &rarr;</a>
  </div>
</section>

<!-- Wave divider: insurance to footer -->
<div class="wave-divider" style="background:var(--sand);">
  <svg viewBox="0 0 1440 60" fill="none" preserveAspectRatio="none" style="height:50px;">
    <path d="M0 25 C300 55 600 0 900 35 C1100 60 1300 10 1440 30 L1440 60 L0 60 Z" fill="#1E3D34"/>
  </svg>
</div>

<?php get_footer(); ?>
