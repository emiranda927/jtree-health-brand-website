<?php
/**
 * Template Name: Crisis Resources
 *
 * @package JTreeHealth
 */
defined('ABSPATH') || exit;
get_header();
?>

<main id="main">

  <section class="section section--intro">
    <div class="container container--prose">
      <span class="jth-eyebrow">If you or your teen is in crisis</span>
      <h1 class="jth-h1 jth-mb-4">Get help right now.</h1>
      <p class="jth-body-l jth-mb-3">If you or your teen is in immediate danger, call <strong>911</strong>. Otherwise, the resources below are free, confidential, and available 24/7.</p>
      <p class="jth-body jth-text-muted">Joshua Tree Health is not a 24/7 crisis line. We are an outpatient program. Please use the resources below when seconds matter; we will be here when you are ready to plan next steps.</p>
    </div>
  </section>

  <section class="section section-bg-cream-2">
    <div class="container container--prose jth-crisis-list">

      <article class="jth-card jth-crisis-card">
        <h2 class="jth-h3 jth-crisis-card__title">988 Suicide &amp; Crisis Lifeline</h2>
        <p class="jth-meta jth-crisis-card__meta">Call or text &middot; 24/7 &middot; Free &middot; Confidential</p>
        <p class="jth-body jth-crisis-card__body">Trained counselors are available around the clock for anyone in emotional distress, suicidal crisis, or supporting someone in either.</p>
        <a class="jth-btn jth-btn-primary" href="tel:988">Call 988</a>
      </article>

      <article class="jth-card jth-crisis-card">
        <h2 class="jth-h3 jth-crisis-card__title">Crisis Text Line</h2>
        <p class="jth-meta jth-crisis-card__meta">Text HOME to 741741 &middot; 24/7 &middot; Free</p>
        <p class="jth-body jth-crisis-card__body">Texting feels safer than talking, and counselors are trained to meet you where you are.</p>
        <a class="jth-btn jth-btn-primary" href="sms:741741&amp;body=HOME">Text HOME to 741741</a>
      </article>

      <article class="jth-card jth-crisis-card">
        <h2 class="jth-h3 jth-crisis-card__title">911</h2>
        <p class="jth-meta jth-crisis-card__meta">Call &middot; 24/7 &middot; For immediate medical or safety emergencies</p>
        <p class="jth-body jth-crisis-card__body">If your teen is unsafe right now &mdash; overdose, active suicide attempt, or violence &mdash; call 911. Tell the dispatcher this is a mental-health emergency and request a CIT-trained officer if possible.</p>
        <a class="jth-btn jth-btn-secondary" href="tel:911">Call 911</a>
      </article>

      <article class="jth-card jth-crisis-card">
        <h2 class="jth-h3 jth-crisis-card__title">The Trevor Project (LGBTQ+ youth)</h2>
        <p class="jth-meta jth-crisis-card__meta">Call 1-866-488-7386 &middot; Text START to 678-678 &middot; 24/7</p>
        <p class="jth-body jth-crisis-card__body jth-crisis-card__body--last">Crisis intervention and suicide prevention for LGBTQ+ young people under 25.</p>
      </article>

      <article class="jth-card jth-crisis-card">
        <h2 class="jth-h3 jth-crisis-card__title">Local emergency rooms</h2>
        <p class="jth-meta jth-crisis-card__meta">Wake County, NC</p>
        <p class="jth-body jth-crisis-card__body jth-crisis-card__body--last">WakeMed Children's, UNC Rex, Duke Raleigh &mdash; all have psychiatric assessment teams. Bring an ID and your insurance card. You can stay with your teen in the ER.</p>
      </article>

    </div>
  </section>

</main>

<?php get_footer(); ?>
