<?php
/**
 * Partial: Footer CTA
 * Sits just above the site footer on most pages.
 * Pass $args['title'] and $args['sub'] to customise per-page.
 *
 * @package JTreeHealth
 */

defined('ABSPATH') || exit;

$title = isset($args['title']) ? $args['title'] : "Your teen doesn't have to keep struggling. Neither do you.";
$sub   = isset($args['sub'])   ? $args['sub']   : "Fill out the form and we'll call you within one business day. Two minutes. No commitment. Just a conversation.";
$home  = esc_url(home_url('/'));
?>

<!-- Wave divider -->
<div class="wave-divider" style="background:var(--sand);">
  <svg viewBox="0 0 1440 60" fill="none" preserveAspectRatio="none" style="height:50px;">
    <path d="M0 25 C300 55 600 0 900 35 C1100 60 1300 10 1440 30 L1440 60 L0 60 Z" fill="#EAE8F5"/>
  </svg>
</div>

<section class="footer-cta" style="position:relative;">
  <h2 class="footer-cta__title" data-reveal><?php echo esc_html($title); ?></h2>
  <p class="footer-cta__sub" data-reveal><?php echo esc_html($sub); ?></p>
  <div class="footer-cta__btns" data-reveal>
    <a href="<?php echo $home; ?>admissions/" class="btn btn--primary btn--lg">Start the Conversation</a>
    <a href="tel:9192764005" class="btn btn--secondary btn--lg">Call (919) 276-4005</a>
  </div>
</section>

<!-- Wave divider -->
<div class="wave-divider" style="background:var(--pale-lav);">
  <svg viewBox="0 0 1440 60" fill="none" preserveAspectRatio="none" style="height:50px;">
    <path d="M0 20 C480 60 960 0 1440 40 L1440 60 L0 60 Z" fill="#1E3D34"/>
  </svg>
</div>
