<?php
/**
 * Partial · Mobile sticky CTA bar.
 *
 * Persistent phone + admissions CTA, mobile-only. Suppressed on the
 * admissions page (form already on screen), thank-you (post-conversion),
 * and crisis (different priority — call 988, not us).
 *
 * @package JTreeHealth
 */
defined('ABSPATH') || exit;

if (is_page(array('admissions', 'thank-you', 'crisis'))) {
    return;
}
?>
<div class="jth-mobile-cta" role="region" aria-label="Quick contact">
  <a class="jth-mobile-cta__call" href="tel:9193355053" aria-label="Call (919) 335-5053">
    <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
      <path fill="currentColor" d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.25 11.4 11.4 0 0 0 3.6.57 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1 11.4 11.4 0 0 0 .57 3.6 1 1 0 0 1-.25 1l-2.22 2.2Z"/>
    </svg>
    <span>Call</span>
  </a>
  <a class="jth-mobile-cta__primary" href="<?php echo esc_url(home_url('/admissions/')); ?>">
    Start the Conversation
  </a>
</div>
