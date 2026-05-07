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
        <span class="jth-eyebrow" style="display:inline-block; margin-bottom:12px;">§ Admissions / Inquiry form</span>
        <h1 class="jth-h1">Start the Conversation.</h1>
        <p class="lede">Tell us a little, and an admissions clinician will reach out within one business day. No clinical detail required up front.</p>

        <?php jtree_render_inquiry_form(); ?>
      </div>
    </div>

    <aside>
      <div class="next-steps">
        <span class="jth-eyebrow">What happens next</span>
        <h2>Three short steps.</h2>
        <ol>
          <li><b>You submit this form.</b> Two minutes, no medical detail required.</li>
          <li><b>We call within one business day.</b> A real admissions clinician — not a sales person.</li>
          <li><b>Together, we plan a start.</b> If we're a fit, we verify insurance and set a date. If we're not, we point you to who is.</li>
        </ol>
      </div>

      <div class="contact-card">
        <h3>Or talk to us now</h3>
        <a class="phone" href="tel:9192764005">(919) 276-4005</a>
        <p class="hours">Mon–Fri · 8 am – 6 pm ET</p>
        <p class="jth-body-s" style="margin: 12px 0 0;">After hours? Leave a message and we'll call you back the next business day. In a crisis, call <strong>988</strong>.</p>
      </div>
    </aside>
  </section>

</main>

<?php get_footer(); ?>
