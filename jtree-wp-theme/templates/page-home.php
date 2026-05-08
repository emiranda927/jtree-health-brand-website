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

  <!-- ─────────────────────────── HERO — kinetic split-flap ─────────────────────────── -->
  <section class="h-hero h-hero--kinetic">
    <div class="h-hero-grid h-hero-grid--single">

      <div class="h-hero-text h-fade">
        <div class="h-hero-kicker">
          <span><strong>Issue №01</strong></span>
          <span>Teen mental health · Apex, NC</span>
        </div>

        <h1 class="h-hero-headline">
          <span class="h-hero-fixed">Grow in a shape that is</span>
          <span class="h-hero-flap">
            <span class="h-hero-flap-spacer">entirely your own</span>
            <span aria-hidden="true">entirely your own</span>
            <span aria-hidden="true">messy and yours</span>
            <span aria-hidden="true">still becoming</span>
            <span aria-hidden="true">shaped by you</span>
          </span><span class="h-hero-period">.</span>
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

  <!-- ─────────────────────── MANIFESTO — pale lavender + Joshie ── -->
  <section class="h-manifesto">
    <div class="h-manifesto-inner">
      <span class="jth-eyebrow">§ 01 / Why we exist</span>
      <blockquote>
        Adolescence is not a problem to be solved. It's a desert to be <em>navigated</em> —
        slowly, in honest weather, with people who know the terrain.
      </blockquote>
    </div>
    <div class="h-manifesto-joshie" aria-hidden="true">
      <img
        src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/joshie/joshie-curled-up-resting.png'); ?>"
        srcset="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/joshie/joshie-curled-up-resting.png'); ?> 1x, <?php echo esc_url(JTREE_THEME_URI . '/assets/brand/joshie/joshie-curled-up-resting@3x.png'); ?> 3x"
        alt="" width="200" height="200" loading="lazy" decoding="async">
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

  <!-- ─────────────────────── QUOTE — clean editorial ─────────── -->
  <section class="h-quote">
    <div class="h-quote-inner">
      <span class="jth-eyebrow">§ 05 / A note from our founder</span>
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

  <!-- ─────────────────────── CTA BAND — Joshie + tree friend ─── -->
  <section class="h-cta">
    <div class="h-cta-inner">
      <span class="jth-eyebrow">§ 06 / Start when you're ready</span>
      <h2>You don't have to figure this out <em>alone.</em></h2>
      <p>An admissions clinician will reach out within one business day. Your information is protected, and we never share it.</p>
      <div class="h-cta-actions">
        <a class="jth-btn jth-btn-primary jth-btn-lg" href="<?php echo esc_url(home_url('/admissions/')); ?>">Start the Conversation</a>
        <a class="jth-btn jth-btn-ghost jth-btn-lg" href="tel:9192764005">(919) 276-4005</a>
      </div>
      <div class="h-cta-joshie">
        <img
          src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/joshie/joshie-with-tree-friend.png'); ?>"
          srcset="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/joshie/joshie-with-tree-friend.png'); ?> 1x, <?php echo esc_url(JTREE_THEME_URI . '/assets/brand/joshie/joshie-with-tree-friend@3x.png'); ?> 3x"
          alt="The Joshua Tree Health mascot standing beside a tree friend"
          width="360" height="320" loading="lazy" decoding="async">
      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>
