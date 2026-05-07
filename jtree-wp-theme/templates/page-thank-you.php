<?php
/**
 * Template Name: Thank You
 *
 * @package JTreeHealth
 */
defined('ABSPATH') || exit;
// GA4 conversion event fires from jtree_thank_you_dataLayer (functions.php).
get_header();
?>

<main id="main" class="thanks">
  <img class="thanks-tree" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/tree-mark-deep-green.png'); ?>" alt="" width="91" height="118" decoding="async" aria-hidden="true">
  <span class="jth-eyebrow" style="display:inline-block; margin-bottom: 14px;">§ We got it</span>
  <h1 class="jth-h1">Thank you.</h1>
  <p class="lede">Your inquiry is in. An admissions clinician will reach out within one business day. We're glad you took this step.</p>
  <p class="jth-hand" style="display:inline-block; transform: rotate(-1.5deg); margin: 16px 0 0;">— breathe.</p>

  <div class="thanks-timeline">
    <h2>What happens next</h2>
    <ol>
      <li><b>Within 24 hours</b> — A clinician calls or emails you back.</li>
      <li><b>Phone consult</b> — A short call to understand what's going on and answer your questions.</li>
      <li><b>Insurance + start date</b> — If we're a fit, we verify coverage and set your teen's first day.</li>
    </ol>
  </div>

  <p class="jth-body" style="margin: 32px auto 8px; max-width: 480px;">If you'd rather call us right now:</p>
  <a class="jth-btn jth-btn-secondary jth-btn-lg" href="tel:9192764005">Call (919) 276-4005</a>

  <p class="jth-body-s" style="margin: 48px auto 0; max-width: 480px;">In a crisis, please call or text <a href="tel:988"><strong>988</strong></a> right now. The Crisis Text Line is reachable by texting <strong>HOME</strong> to <a href="sms:741741"><strong>741741</strong></a>.</p>
</main>

<?php get_footer(); ?>
