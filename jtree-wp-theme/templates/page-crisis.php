<?php
/**
 * Template Name: Crisis Resources
 * Description: Crisis help and emergency resources
 *
 * @package JTreeHealth
 */

get_header();
?>

<?php get_template_part('templates/partials/crisis-bar'); ?>

<!-- Page Header -->
<section class="jtree-page-header jtree-section--pale-lavender jtree-page-header--with-motif">
  <div class="jtree-container">
    <h1>Crisis Resources</h1>
    <p class="jtree-lead">If your teen is in immediate danger, call 911. For crisis support, use the resources below. They are free, confidential, and available 24/7.</p>
  </div>
</section>

<!-- Crisis Resources -->
<section class="jtree-section jtree-section--warm-sand">
  <div class="jtree-container">
    <div class="jtree-container--narrow" style="margin: 0 auto;">

      <div class="jtree-crisis-resource">
        <h3>988 Suicide &amp; Crisis Lifeline</h3>
        <a href="tel:988" class="jtree-crisis-resource__phone">Call or text 988</a>
        <p>The 988 Suicide &amp; Crisis Lifeline provides free, confidential support for people in distress, 24 hours a day, 7 days a week. Available in English and Spanish.</p>
        <p><a href="https://988lifeline.org/" target="_blank" rel="noopener noreferrer">Visit 988lifeline.org &rarr;</a></p>
      </div>

      <div class="jtree-crisis-resource">
        <h3>Crisis Text Line</h3>
        <p class="jtree-crisis-resource__phone">Text HOME to 741741</p>
        <p>Free, 24/7 crisis support via text message. A trained crisis counselor will respond. Available for anyone in crisis.</p>
        <p><a href="https://www.crisistextline.org/" target="_blank" rel="noopener noreferrer">Visit crisistextline.org &rarr;</a></p>
      </div>

      <div class="jtree-crisis-resource">
        <h3>Emergency Services</h3>
        <a href="tel:911" class="jtree-crisis-resource__phone">Call 911</a>
        <p>If your teen is in immediate danger of harming themselves or others, call 911 or go to your nearest emergency room.</p>
      </div>

      <div class="jtree-crisis-resource">
        <h3>SAMHSA National Helpline</h3>
        <a href="tel:18006624357" class="jtree-crisis-resource__phone">1-800-662-4357</a>
        <p>Free, confidential, 24/7, 365-day-a-year treatment referral and information service (in English and Spanish) for individuals and families facing mental and/or substance use disorders.</p>
        <p><a href="https://www.samhsa.gov/find-help/national-helpline" target="_blank" rel="noopener noreferrer">Visit SAMHSA &rarr;</a></p>
      </div>

      <div class="jtree-crisis-resource">
        <h3>The Trevor Project (LGBTQ+ Youth)</h3>
        <a href="tel:18664887386" class="jtree-crisis-resource__phone">1-866-488-7386</a>
        <p>Crisis intervention and suicide prevention for LGBTQ+ young people under 25. Also available via text (text START to 678-678) and chat.</p>
        <p><a href="https://www.thetrevorproject.org/" target="_blank" rel="noopener noreferrer">Visit thetrevorproject.org &rarr;</a></p>
      </div>

    </div>
  </div>
</section>

<!-- JTree is not a crisis service -->
<section class="jtree-section jtree-section--pale-sage">
  <div class="jtree-container jtree-text-center">
    <div class="jtree-container--narrow" style="margin: 0 auto;">
      <h2>JTree Health is not a crisis service.</h2>
      <p>We provide PHP and IOP programs for teens &mdash; not emergency or inpatient care. If your teen is in crisis right now, please use the resources above.</p>
      <p>If your teen has stabilized after a crisis and you&rsquo;re looking for structured outpatient support, we may be able to help. Call us at <a href="tel:+19192764005">(919) 276-4005</a> to discuss your situation.</p>
    </div>
  </div>
</section>

<?php get_footer(); ?>
