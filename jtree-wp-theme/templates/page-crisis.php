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

  <section class="section" style="padding-top: 64px; padding-bottom: 24px;">
    <div class="container" style="max-width: 760px;">
      <span class="jth-eyebrow" style="display:inline-block; margin-bottom: 12px;">If you or your teen is in crisis</span>
      <h1 class="jth-h1" style="margin: 0 0 16px;">Get help right now.</h1>
      <p class="jth-body-l" style="margin: 0 0 12px;">If you or your teen is in immediate danger, call <strong>911</strong>. Otherwise, the resources below are free, confidential, and available 24/7.</p>
      <p class="jth-body" style="margin: 0; color: var(--jth-fg-muted);">Joshua Tree Health is not a 24/7 crisis line. We are an outpatient program. Please use the resources below when seconds matter; we will be here when you are ready to plan next steps.</p>
    </div>
  </section>

  <section class="section section-bg-cream-2">
    <div class="container" style="max-width: 760px; display: flex; flex-direction: column; gap: 16px;">

      <article class="jth-card">
        <h2 class="jth-h3" style="margin: 0 0 6px;">988 Suicide &amp; Crisis Lifeline</h2>
        <p class="jth-meta" style="margin: 0 0 12px;">Call or text &middot; 24/7 &middot; Free &middot; Confidential</p>
        <p class="jth-body" style="margin: 0 0 14px;">Trained counselors are available around the clock for anyone in emotional distress, suicidal crisis, or supporting someone in either.</p>
        <a class="jth-btn jth-btn-primary" href="tel:988">Call 988</a>
      </article>

      <article class="jth-card">
        <h2 class="jth-h3" style="margin: 0 0 6px;">Crisis Text Line</h2>
        <p class="jth-meta" style="margin: 0 0 12px;">Text HOME to 741741 &middot; 24/7 &middot; Free</p>
        <p class="jth-body" style="margin: 0 0 14px;">Texting feels safer than talking, and counselors are trained to meet you where you are.</p>
        <a class="jth-btn jth-btn-primary" href="sms:741741&amp;body=HOME">Text HOME to 741741</a>
      </article>

      <article class="jth-card">
        <h2 class="jth-h3" style="margin: 0 0 6px;">911</h2>
        <p class="jth-meta" style="margin: 0 0 12px;">Call &middot; 24/7 &middot; For immediate medical or safety emergencies</p>
        <p class="jth-body" style="margin: 0 0 14px;">If your teen is unsafe right now &mdash; overdose, active suicide attempt, or violence &mdash; call 911. Tell the dispatcher this is a mental-health emergency and request a CIT-trained officer if possible.</p>
        <a class="jth-btn jth-btn-secondary" href="tel:911">Call 911</a>
      </article>

      <article class="jth-card">
        <h2 class="jth-h3" style="margin: 0 0 6px;">The Trevor Project (LGBTQ+ youth)</h2>
        <p class="jth-meta" style="margin: 0 0 12px;">Call 1-866-488-7386 &middot; Text START to 678-678 &middot; 24/7</p>
        <p class="jth-body" style="margin: 0;">Crisis intervention and suicide prevention for LGBTQ+ young people under 25.</p>
      </article>

      <article class="jth-card">
        <h2 class="jth-h3" style="margin: 0 0 6px;">Local emergency rooms</h2>
        <p class="jth-meta" style="margin: 0 0 12px;">Wake County, NC</p>
        <p class="jth-body" style="margin: 0;">WakeMed Children's, UNC Rex, Duke Raleigh &mdash; all have psychiatric assessment teams. Bring an ID and your insurance card. You can stay with your teen in the ER.</p>
      </article>

    </div>
  </section>

</main>

<?php get_footer(); ?>
