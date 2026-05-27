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
        <div class="h-hero-kicker jth-reveal">
          <span><strong>Issue №01</strong></span>
          <span>Teen mental health · Apex, NC</span>
        </div>

        <h1 class="h-hero-headline jth-reveal jth-reveal--delay-1">
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
          Joshua Tree Health is an adolescent <strong>PHP and IOP</strong> program in Apex, NC, for teens ages 10&#8211;17. DBT, family work, and nervous-system support, delivered with respect for your kid's intelligence and skepticism. We help your teen build the roots to thrive.
        </p>

        <div class="h-hero-ctas">
          <a class="jth-btn jth-btn-primary jth-btn-lg jth-btn-arrow" href="<?php echo esc_url(home_url('/admissions/')); ?>">
            Start the Conversation
            <span class="jth-btn-arrow__icon" aria-hidden="true">
              <svg viewBox="0 0 14 14" fill="none" focusable="false">
                <path d="M3.5 7h7M7 3.5l3.5 3.5L7 10.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </span>
          </a>
          <a class="jth-btn jth-btn-ghost jth-btn-lg" href="<?php echo esc_url(home_url('/programs/')); ?>">Explore Programs &rarr;</a>
          <span class="meta">Reply within 2&ndash;4 business hours</span>
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
            <span>Cigna</span>
            <span>Tricare</span>
            <span aria-hidden="true">BlueCross BlueShield</span>
            <span aria-hidden="true">Aetna</span>
            <span aria-hidden="true">Cigna</span>
            <span aria-hidden="true">Tricare</span>
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
        <p class="meta">&sect; 02 &nbsp;&middot;&nbsp; Programs</p>
        <h2 class="lead">Structured outpatient care, <em>shaped to fit a school week.</em></h2>
      </div>
      <p class="h-programs-body">
        No inpatient stay required. Every program is built around the rhythms of family
        life &mdash; pickup lines, dinner, homework &mdash; so your teen can keep being a teen
        while doing real work.
      </p>
    </div>

    <div class="h-programs-grid">
      <a class="h-pcard h-pcard--live" href="<?php echo esc_url(home_url('/programs/')); ?>#iop">
        <span class="h-pcard__status">Open today</span>
        <div class="h-pcard-num">&#8470; 01 &nbsp;&middot;&nbsp; IOP</div>
        <h3 class="h-pcard-title">Intensive Outpatient</h3>
        <div class="h-pcard-schedule">In-person + virtual &middot; multiple times per week &middot; 12 weeks</div>
        <p class="h-pcard-body">After-school care for teens who can stay enrolled in school. Skills group, individual therapy, and family sessions on a regular cadence.</p>
        <span class="h-pcard-arrow">Learn about IOP &nbsp;&rarr;</span>
      </a>
      <a class="h-pcard" href="<?php echo esc_url(home_url('/programs/')); ?>#php">
        <div class="h-pcard-num">&#8470; 02 &nbsp;&middot;&nbsp; PHP <span class="jth-pill jth-pill-soon jth-pill-tag">Coming soon</span></div>
        <h3 class="h-pcard-title">Partial Hospitalization</h3>
        <div class="h-pcard-schedule">Mon&ndash;Fri &middot; 9 am &ndash; 3 pm</div>
        <p class="h-pcard-body">Launching at Joshua Tree Health: a five-day-a-week structure for teens needing the most support, including group, individual, and family work plus school accommodations woven through the day.</p>
        <span class="h-pcard-arrow">Learn about PHP &nbsp;&rarr;</span>
      </a>
    </div>
  </section>

  <!-- ─────────────────────── FIGURES / STATS ────────────────── -->
  <section class="h-figures">
    <div class="h-figures-head">
      <div class="num">&sect; 03 &nbsp;&middot;&nbsp; By the numbers</div>
      <h2>What you can <em>count on.</em></h2>
    </div>
    <div class="h-figures-grid">
      <div class="h-fig">
        <div class="h-fig-num">10&ndash;17</div>
        <div class="h-fig-label">Ages we serve</div>
        <p class="h-fig-body">We don't see adults. Every clinician here is trained for adolescent work.</p>
      </div>
      <div class="h-fig">
        <div class="h-fig-num">2&ndash;4<span class="frac"> hr</span></div>
        <div class="h-fig-label">Reply window</div>
        <p class="h-fig-body">From the moment you submit the inquiry form to the moment an admissions clinician calls you back.</p>
      </div>
      <div class="h-fig">
        <div class="h-fig-num">0</div>
        <div class="h-fig-label">Inpatient stays required</div>
        <p class="h-fig-body">Our IOP keeps teens at home, in school, and sleeping in their own bed. No 30-day mandate, no residential.</p>
      </div>
      <div class="h-fig">
        <div class="h-fig-num">CARF</div>
        <div class="h-fig-label">Accreditation</div>
        <p class="h-fig-body">CARF accreditation is an external standard of clinical quality, ethics, and outcomes. Not a marketing badge.</p>
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
      <p>From the first call to your teen's first day, we walk it with you. No sales tactics, no jargon.</p>
    </div>

    <div class="h-process-grid">
      <div class="h-step">
        <div class="h-step-num">01</div>
        <h3>Fill out the form</h3>
        <p>Two minutes. No clinical detail needed up front, no awkward intake script.</p>
        <span class="duration">~ 2 min</span>
      </div>
      <div class="h-step">
        <div class="h-step-num">02</div>
        <h3>We call you back</h3>
        <p>A real admissions clinician, not a scheduler. We listen first.</p>
        <span class="duration">2&ndash;4 business hours</span>
      </div>
      <div class="h-step">
        <div class="h-step-num">03</div>
        <h3>Start within the week</h3>
        <p>If we're the right fit, we verify insurance and set a date. If we aren't, we'll point you to who is.</p>
        <span class="duration">~ 1 week to start</span>
      </div>
    </div>
  </section>

  <!-- ─────────────────────── QUOTE — clean editorial ─────────── -->
  <section class="h-quote">
    <div class="h-quote-inner">
      <span class="jth-eyebrow">§ 05 / A note from our founder</span>
      <blockquote class="jth-reveal">
        I built Joshua Tree Health because the kind of care I wish my brother Josh had didn't exist near us. So we made it &mdash; <em>for the families coming next.</em>
      </blockquote>
      <cite class="jth-reveal jth-reveal--delay-1">Gabriela Miranda &nbsp;/&nbsp; Founder</cite>
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
        <a class="jth-btn jth-btn-primary jth-btn-lg" href="<?php echo esc_url(home_url('/contact/')); ?>">Refer a patient</a>
        <a class="jth-btn jth-btn-secondary jth-btn-lg" href="tel:9193355053">Call (919) 335-5053</a>
      </div>
    </div>
  </section>

  <!-- ─────────────────────── CTA BAND — Joshie + tree friend ─── -->
  <section class="h-cta">
    <div class="h-cta-inner">
      <span class="jth-eyebrow">&sect; 07 / Start when you're ready</span>
      <h2>You don't have to figure this out <em>alone.</em></h2>
      <p>An admissions clinician will call you back within 2 to 4 business hours. Your information is protected, and we never share it.</p>
      <div class="h-cta-actions">
        <a class="jth-btn jth-btn-primary jth-btn-lg" href="<?php echo esc_url(home_url('/admissions/')); ?>">Start the Conversation</a>
        <a class="jth-btn jth-btn-ghost jth-btn-lg" href="tel:9193355053">(919) 335-5053</a>
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
