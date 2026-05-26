<?php
/**
 * Template Name: Admissions
 *
 * @package JTreeHealth
 */
defined('ABSPATH') || exit;
get_header();

?>

<main id="main" class="admissions-section">

  <section class="admissions-grid">
    <div>
      <div class="form-card">
        <span class="jth-eyebrow" style="display:inline-block; margin-bottom:12px;">&sect; Admissions</span>
        <h1 class="jth-h1">Start the Conversation.</h1>
        <p class="lede">Tell us a little about your teen. An admissions clinician will call you back within 2 to 4 business hours &mdash; no clinical detail needed up front. We'll work that out together.</p>
        <p class="jth-body-s" style="margin: 8px 0 24px; color: var(--jth-fg-muted);">In-network with BlueCross BlueShield, Cigna, Aetna, and Tricare. We verify your coverage before your teen's first day.</p>

        <?php jtree_render_inquiry_form(); ?>
      </div>
    </div>

    <aside>
      <div class="next-steps">
        <span class="jth-eyebrow">What happens next</span>
        <h2>Three short steps.</h2>
        <ol>
          <li><b>Fill out the form.</b> Two minutes. Just enough so we can call you back ready.</li>
          <li><b>We call you within 2 to 4 business hours.</b> A real admissions clinician, not a sales call.</li>
          <li><b>If we're a fit, we start within the week.</b> We verify insurance, set a date, and walk you through what to expect. If we're not the right fit, we'll point you to who is.</li>
        </ol>
      </div>

      <div class="contact-card">
        <h3>Or talk to us now</h3>
        <a class="phone" href="tel:9193355053">(919) 335-5053</a>
        <p class="hours">Mon&ndash;Fri &middot; 9 am &ndash; 5 pm ET</p>
        <p class="jth-body-s" style="margin: 12px 0 0;">After hours? Leave a message and we'll call you back the next business day. In a crisis, call or text <a href="tel:988"><strong>988</strong></a>.</p>
      </div>
    </aside>
  </section>

</main>

<?php get_footer(); ?>
