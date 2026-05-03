<?php
/**
 * Template Name: Contact
 * Description: Contact page with phone, address, and inquiry form
 *
 * @package JTreeHealth
 */

get_header();
?>

<?php get_template_part('templates/partials/crisis-bar'); ?>

<!-- Page Header -->
<section class="jtree-page-header jtree-section--warm-sand jtree-page-header--with-motif">
  <div class="jtree-container">
    <span class="jtree-hero__badge">Contact Us</span>
    <h1>We&rsquo;re here when you&rsquo;re ready.</h1>
    <p class="jtree-lead">Call us, fill out the form, or stop by. A real person answers.</p>
  </div>
</section>

<!-- Contact Info -->
<section class="jtree-section jtree-section--pale-lavender">
  <div class="jtree-container">
    <div class="jtree-grid jtree-grid--3">

      <div class="jtree-card jtree-text-center">
        <h3>Phone</h3>
        <a href="tel:+19192764005" class="jtree-crisis-resource__phone">(919) 276-4005</a>
        <p>Monday &ndash; Friday, 9am &ndash; 6pm</p>
      </div>

      <div class="jtree-card jtree-text-center">
        <h3>Location</h3>
        <p><strong>Apex, NC</strong></p>
        <p>Serving the NC Triangle &mdash; Cary, Raleigh, Durham, Chapel Hill, and surrounding communities.</p>
      </div>

      <div class="jtree-card jtree-text-center">
        <h3>Email</h3>
        <p><a href="mailto:info@jtreehealth.com">info@jtreehealth.com</a></p>
        <p>We respond within one business day.</p>
      </div>

    </div>
  </div>
</section>

<!-- Inquiry Form -->
<section class="jtree-section jtree-section--pale-sage">
  <div class="jtree-container">
    <div class="jtree-section-header">
      <h2>Send Us a Message</h2>
      <p>Complete the form and we&rsquo;ll reach out within one business day.</p>
    </div>
    <?php jtree_render_inquiry_form(); ?>
  </div>
</section>

<?php get_footer(); ?>
