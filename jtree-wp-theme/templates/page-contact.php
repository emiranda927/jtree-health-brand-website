<?php
/**
 * Template Name: Contact
 *
 * @package JTreeHealth
 */
defined('ABSPATH') || exit;
get_header();
?>

<main id="main">

  <section class="section" style="padding-top: 64px; padding-bottom: 24px;">
    <div class="container" style="max-width: 880px;">
      <span class="jth-eyebrow" style="display:inline-block; margin-bottom: 12px;">Contact</span>
      <h1 class="jth-display-l" style="font-size: clamp(40px, 5vw, 56px); margin: 0 0 20px;">Reach a real human, <em class="jth-emph">on the first try.</em></h1>
      <p class="jth-body-l" style="margin: 0; max-width: 60ch;">Phone is fastest. The form is fine too &mdash; we reply within one business day.</p>
    </div>
  </section>

  <section class="section">
    <div class="container" style="max-width: 880px;">
      <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 32px;">
        <div class="contact-card" style="padding: 32px;">
          <h3 style="margin: 0 0 14px;">Call us</h3>
          <a class="phone" href="tel:9192764005" style="font-size: 28px;">(919) 276-4005</a>
          <p class="hours">Mon&ndash;Fri &middot; 8 am &ndash; 6 pm ET</p>
          <p class="jth-body-s" style="margin: 18px 0 0;">After hours? Leave a message and we'll call you back the next business day. In a crisis, call or text <strong>988</strong>.</p>
        </div>
        <div class="contact-card" style="padding: 32px;">
          <h3 style="margin: 0 0 14px;">Visit</h3>
          <p class="jth-body" style="margin: 0 0 8px;">Apex, NC 27502</p>
          <p class="jth-body-s" style="margin: 0 0 18px; color: var(--jth-fg-muted);">Street address provided after intake. Parking is on-site and free.</p>
          <p class="jth-meta" style="margin: 0 0 4px;">PHP: Mon&ndash;Fri 9 am &ndash; 3 pm</p>
          <p class="jth-meta" style="margin: 0;">IOP: Tue / Wed / Thu 3 &ndash; 6 pm</p>
        </div>
      </div>
      <div style="margin-top: 32px;">
        <a class="jth-btn jth-btn-primary jth-btn-lg" href="<?php echo esc_url(home_url('/admissions/')); ?>">Start the Conversation</a>
      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>
