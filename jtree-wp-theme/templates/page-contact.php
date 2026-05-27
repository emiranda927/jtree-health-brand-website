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

  <section class="section section--intro">
    <div class="container container--narrow">
      <span class="jth-eyebrow">Contact</span>
      <h1 class="jth-display-l">Reach a real human, <em class="jth-emph">on the first try.</em></h1>
      <p class="jth-body-l jth-lede">Phone is fastest. The form is fine too &mdash; we reply within 2 to 4 business hours.</p>
    </div>
  </section>

  <section class="section">
    <div class="container container--narrow">
      <div class="jth-grid-pair">
        <div class="contact-card contact-card--lg">
          <h3 class="jth-mb-4">Call us</h3>
          <a class="phone phone--lg" href="tel:9193355053">(919) 335-5053</a>
          <p class="hours">Mon&ndash;Fri &middot; 9 am &ndash; 5 pm ET</p>
          <p class="jth-body-s jth-mt-5">After hours? Leave a message and we'll call you back the next business day. In a crisis, call or text <strong>988</strong>.</p>
        </div>
        <div class="contact-card contact-card--lg">
          <h3 class="jth-mb-4">Visit</h3>
          <p class="jth-body jth-mb-2">800 West Williams St., STE 203<br>Apex, NC 27502</p>
          <p class="jth-body-s contact-card__hint">Parking is on-site and free.</p>
          <p class="jth-meta contact-card__schedule">PHP: Mon&ndash;Fri 9 am &ndash; 3 pm <span class="contact-card__soon">(coming soon)</span></p>
          <p class="jth-meta contact-card__schedule">IOP in-person: Mon, Tue, Thu 4&ndash;7 pm</p>
          <p class="jth-meta contact-card__schedule">IOP virtual: Tue, Thu, Sat 9 am&ndash;12 pm</p>
          <p class="jth-meta contact-card__schedule contact-card__schedule--last">Individual + family: Wed, Fri</p>
        </div>
      </div>
      <div class="jth-mt-7">
        <a class="jth-btn jth-btn-primary jth-btn-lg jth-btn-arrow" href="<?php echo esc_url(home_url('/admissions/')); ?>">
          Start the Conversation
          <span class="jth-btn-arrow__icon" aria-hidden="true">
            <svg viewBox="0 0 14 14" fill="none" focusable="false">
              <path d="M3.5 7h7M7 3.5l3.5 3.5L7 10.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </span>
        </a>
      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>
