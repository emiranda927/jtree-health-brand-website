<?php
/**
 * Template Name: Home
 *
 * @package JTreeHealth
 */
defined('ABSPATH') || exit;
get_header();

?>

<main id="main">

  <!-- ─────────────────────────── HERO ─────────────────────────── -->
  <section class="h-hero">
    <div class="h-hero-grid">

      <div class="h-hero-text h-fade">
        <div class="h-hero-kicker">
          <span><strong>Issue №01</strong></span>
          <span>Teen mental health · Apex, NC</span>
        </div>

        <h1>
          Grow in a shape <br>
          that is <em>entirely</em> <br>
          your own<span class="h-hero-period">.</span>
        </h1>

        <p class="h-hero-sub">
          Joshua Tree Health is an adolescent <strong>PHP &amp; IOP</strong> for teens ages
          10&#8211;17 — trauma-informed DBT delivered with respect for your kid's
          intelligence and skepticism. We don't fix anyone. We help them build roots.
        </p>

        <div class="h-hero-ctas">
          <a class="jth-btn jth-btn-primary jth-btn-lg" href="<?php echo esc_url(home_url('/admissions/')); ?>">Start the Conversation</a>
          <a class="jth-btn jth-btn-ghost jth-btn-lg" href="<?php echo esc_url(home_url('/programs/')); ?>">Explore Programs →</a>
          <span class="meta">Reply within 1 business day</span>
        </div>
      </div>

      <div class="h-hero-art h-fade d2">
        <div class="h-hero-art-frame">
          <img class="h-hero-photo" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/collage-torn-desert-photo.png'); ?>" alt="A Joshua tree on a quiet desert hillside." width="800" height="1000" fetchpriority="high" decoding="async">
        </div>
        <img class="h-hero-tape-1" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/collage-purple-tape.png'); ?>" alt="" width="240" height="80" loading="lazy" decoding="async" aria-hidden="true">
        <img class="h-hero-tape-2" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/collage-purple-tape.png'); ?>" alt="" width="240" height="80" loading="lazy" decoding="async" aria-hidden="true">
        <img class="h-hero-twinkle" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/twinkle-plus-lavender.svg'); ?>" alt="" width="40" height="40" loading="lazy" aria-hidden="true">
        <div class="h-hero-caption">
          <span>Fig. 01 / Mojave Desert</span>
          <span>JTree Health · 2026</span>
        </div>
      </div>

    </div>
  </section>

  <!-- ─────────────────────── TRUST STRIP ────────────────────── -->
  <div class="h-trust">
    <div class="h-trust-row">
      <span class="h-trust-label">Accredited &amp; in-network with</span>
      <div class="h-trust-items">
        <span class="carf-fixed">CARF Accredited</span>
        <div class="susan">
          <div class="susan-track" aria-hidden="false">
            <span>BlueCross BlueShield</span>
            <span>Aetna</span>
            <span>UnitedHealthcare</span>
            <span>Cigna</span>
            <span>Medicaid (NC)</span>
            <span aria-hidden="true">BlueCross BlueShield</span>
            <span aria-hidden="true">Aetna</span>
            <span aria-hidden="true">UnitedHealthcare</span>
            <span aria-hidden="true">Cigna</span>
            <span aria-hidden="true">Medicaid (NC)</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ─────────────────────── MANIFESTO ──────────────────────── -->
  <section class="h-manifesto">
    <img class="h-manifesto-mark scribble" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/collage-yellow-scribble.png'); ?>" alt="" width="320" height="160" loading="lazy" decoding="async" aria-hidden="true">
    <img class="h-manifesto-mark brush" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/collage-lime-brush.png'); ?>" alt="" width="320" height="160" loading="lazy" decoding="async" aria-hidden="true">
    <div class="h-manifesto-inner">
      <div class="h-manifesto-num">§ 01 &nbsp;·&nbsp; Why we exist</div>
      <blockquote>
        Adolescence is not a problem to be solved. It's a desert to be <em>navigated</em> —
        slowly, in honest weather, with people who know the terrain.
      </blockquote>
    </div>
  </section>

  <!-- ─────────────────────── PROGRAMS ───────────────────────── -->
  <section class="h-programs">
    <div class="h-programs-head">
      <div>
        <p class="meta">§ 02 &nbsp;·&nbsp; Programs</p>
        <h2 class="lead">Three levels of structured support, <em>shaped to fit a school week.</em></h2>
      </div>
      <p class="h-programs-body">
        No inpatient stay required. Every program is built around the rhythms of family
        life — pickup lines, dinner, homework — so your teen can keep being a teen
        while doing real work.
      </p>
    </div>

    <div class="h-programs-grid">
      <a class="h-pcard" href="<?php echo esc_url(home_url('/programs/')); ?>#php">
        <div class="h-pcard-num">№ 01 &nbsp;·&nbsp; PHP</div>
        <h3 class="h-pcard-title">Partial Hospitalization</h3>
        <div class="h-pcard-schedule">Mon–Fri · 9 am – 3 pm · 6–8 wks</div>
        <p class="h-pcard-body">Five-day-a-week structure for teens needing the most support — group, individual, family work, and school accommodations woven through the day.</p>
        <span class="h-pcard-arrow">Learn about PHP &nbsp;→</span>
      </a>
      <a class="h-pcard" href="<?php echo esc_url(home_url('/programs/')); ?>#iop">
        <div class="h-pcard-num">№ 02 &nbsp;·&nbsp; IOP</div>
        <h3 class="h-pcard-title">Intensive Outpatient</h3>
        <div class="h-pcard-schedule">Tue / Wed / Thu · 3–6 pm · 8–12 wks</div>
        <p class="h-pcard-body">After-school care for teens who can stay enrolled in school. Skills group, individual therapy, and family sessions on a regular cadence.</p>
        <span class="h-pcard-arrow">Learn about IOP &nbsp;→</span>
      </a>
      <a class="h-pcard" href="<?php echo esc_url(home_url('/programs/')); ?>#medmgmt">
        <div class="h-pcard-num">№ 03 &nbsp;·&nbsp; Med Mgmt</div>
        <h3 class="h-pcard-title">Medication Management</h3>
        <div class="h-pcard-schedule">By appointment · 30–45 min</div>
        <p class="h-pcard-body">Psychiatry from a clinician who actually knows your teen. We coordinate with your existing prescriber when that's the right call.</p>
        <span class="h-pcard-arrow">Learn about Med Mgmt &nbsp;→</span>
      </a>
    </div>
  </section>

  <!-- ─────────────────────── FIGURES / STATS ────────────────── -->
  <section class="h-figures">
    <div class="h-figures-head">
      <div class="num">§ 03 &nbsp;·&nbsp; By the numbers</div>
      <h2>Measured care, <em>not slogans.</em></h2>
    </div>
    <div class="h-figures-grid">
      <div class="h-fig">
        <div class="h-fig-num">82<span class="pct">%</span></div>
        <div class="h-fig-label">Distress reduction</div>
        <p class="h-fig-body">Of teens report a clinically meaningful drop in distress at discharge.</p>
      </div>
      <div class="h-fig">
        <div class="h-fig-num">9.4<span class="frac">/10</span></div>
        <div class="h-fig-label">Parent satisfaction</div>
        <p class="h-fig-body">Average rating of the care team across the past year of admissions.</p>
      </div>
      <div class="h-fig">
        <div class="h-fig-num">10–17</div>
        <div class="h-fig-label">Ages served</div>
        <p class="h-fig-body">We don't see adults. Every clinician on staff is teen-trained.</p>
      </div>
      <div class="h-fig">
        <div class="h-fig-num">2<span class="frac"> wks</span></div>
        <div class="h-fig-label">Inquiry → start</div>
        <p class="h-fig-body">Typical time from your first call to the day your teen begins programming.</p>
      </div>
    </div>
  </section>

  <!-- ─────────────────────── PROCESS ─────────────────────────── -->
  <section class="h-process">
    <div class="h-process-head">
      <div>
        <div class="num">§ 04 &nbsp;·&nbsp; How it works</div>
        <h2>Three steps. <em>We do the heavy lifting.</em></h2>
      </div>
      <p>From the first call to your teen's first day — we walk it with you. No sales tactics, no pressure, no jargon.</p>
    </div>

    <div class="h-process-grid">
      <div class="h-step">
        <div class="h-step-num">01</div>
        <h3>Tell us a little</h3>
        <p>A two-minute form or a short call. No clinical detail required, no awkward intake script.</p>
        <span class="duration">~ 2 min</span>
      </div>
      <div class="h-step">
        <div class="h-step-num">02</div>
        <h3>A real conversation</h3>
        <p>An admissions clinician (a real human, not a scheduler) calls you within one business day. We listen first.</p>
        <span class="duration">Within 1 business day</span>
      </div>
      <div class="h-step">
        <div class="h-step-num">03</div>
        <h3>A plan, together</h3>
        <p>If we're the right fit, we set a start date and verify your insurance. If we aren't, we'll point you to who is.</p>
        <span class="duration">~ 2 weeks to start</span>
      </div>
    </div>
  </section>

  <!-- ─────────────────────── QUOTE ───────────────────────────── -->
  <section class="h-quote">
    <img class="h-quote-mark brush" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/collage-lime-brush.png'); ?>" alt="" width="320" height="160" loading="lazy" decoding="async" aria-hidden="true">
    <img class="h-quote-mark tw1" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/twinkle-outline-yellow.svg'); ?>" alt="" width="40" height="40" loading="lazy" aria-hidden="true">
    <img class="h-quote-mark tw2" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/twinkle-plus-lavender.svg'); ?>" alt="" width="32" height="32" loading="lazy" aria-hidden="true">
    <div class="h-quote-inner">
      <div class="num">§ 05 &nbsp;·&nbsp; A note from our founder</div>
      <blockquote>
        Resilience isn't about escaping the desert. It's about <em>building the roots</em> to thrive within it.
      </blockquote>
      <cite>Gabriela Miranda &nbsp;/&nbsp; Founder &amp; Clinical Director</cite>
    </div>
  </section>

  <!-- ─────────────────────── REFERRALS ───────────────────────── -->
  <section class="h-refer">
    <div class="h-refer-inner">
      <div>
        <div class="h-refer-num">§ 06 &nbsp;·&nbsp; For providers</div>
        <h2>Referrals <em>welcome.</em></h2>
        <p>Pediatricians, school counselors, outpatient therapists — we make it easy. One call, a warm handoff, ongoing communication with the referring clinician.</p>
      </div>
      <div class="h-refer-actions">
        <a class="jth-btn jth-btn-primary jth-btn-lg" href="#">Refer a patient</a>
        <a class="jth-btn jth-btn-secondary jth-btn-lg" href="tel:9192764005">Call (919) 276-4005</a>
      </div>
    </div>
  </section>

  <!-- ─────────────────────── CTA BAND ────────────────────────── -->
  <section class="h-cta">
    <img class="h-cta-mark swash" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/collage-lime-swash.png'); ?>" alt="" width="320" height="160" loading="lazy" decoding="async" aria-hidden="true">
    <img class="h-cta-mark tape" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/collage-purple-tape.png'); ?>" alt="" width="240" height="80" loading="lazy" decoding="async" aria-hidden="true">
    <img class="h-cta-mark tw" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/twinkle-plus-lavender.svg'); ?>" alt="" width="40" height="40" loading="lazy" aria-hidden="true">
    <div class="h-cta-inner">
      <img class="h-cta-tree" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/tree-mark-deep-green.svg'); ?>" alt="" width="91" height="118" loading="lazy" aria-hidden="true">
      <h2>You don't have to <em>figure this out alone.</em></h2>
      <p>An admissions clinician will reach out within one business day. Your information is protected, and we never share it.</p>
      <div class="h-cta-actions">
        <a class="jth-btn jth-btn-primary jth-btn-lg" href="<?php echo esc_url(home_url('/admissions/')); ?>">Start the Conversation</a>
        <a class="jth-btn jth-btn-ghost jth-btn-lg" href="tel:9192764005">(919) 276-4005</a>
      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>
