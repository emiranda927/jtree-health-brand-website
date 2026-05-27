<?php
/**
 * Template Name: Privacy Policy
 *
 * @package JTreeHealth
 */
defined('ABSPATH') || exit;
get_header();
?>

<main id="main">

  <section class="section section--intro">
    <div class="container container--prose">
      <span class="jth-eyebrow">Privacy policy</span>
      <h1 class="jth-h1 jth-mb-4">Your data, in plain English.</h1>
      <p class="jth-meta jth-mb-0">Last updated: <?php echo esc_html(date('F j, Y')); ?></p>
    </div>
  </section>

  <section class="section">
    <div class="container container--prose jth-prose-page">

      <p class="jth-body-l jth-mb-3"><strong>The short version:</strong></p>
      <ul class="jth-list jth-mb-7">
        <li>We collect the fields you submit on the admissions form, plus anonymous analytics (no city, no IP).</li>
        <li>We do not sell your data, and we do not use ad-network tracking pixels.</li>
        <li>The form data goes directly to our admissions team. The website itself never stores it.</li>
        <li>Once your teen is in care, a separate HIPAA-protected system holds the clinical record.</li>
      </ul>
      <p class="jth-body-s jth-text-muted jth-mb-7">Counsel-reviewed legal language replaces this summary at launch.</p>

      <h2 class="jth-h3 jth-prose-page__h">What we collect on this site</h2>
      <ul class="jth-prose-page__list">
        <li>The fields you submit on the admissions form: name, email, phone, your teen's age, program interest, best time to call, and how you found us.</li>
        <li>Anonymous analytics: pages visited, device type, country (not city). IP addresses are anonymized in our analytics tool.</li>
        <li>Cookies required for the cookie banner to remember your choice. No advertising or tracking cookies without consent.</li>
      </ul>

      <h2 class="jth-h3 jth-prose-page__h">What we don't do</h2>
      <ul class="jth-prose-page__list">
        <li>We do not sell your data to anyone.</li>
        <li>We do not use Meta, TikTok, LinkedIn, or other ad-network pixels.</li>
        <li>We do not record your session or capture form-field values in analytics.</li>
        <li>We do not store any clinical detail submitted on the site beyond the inquiry form fields above.</li>
      </ul>

      <h2 class="jth-h3 jth-prose-page__h">Where the form data goes</h2>
      <p>When you submit the admissions form, the data is sent directly to our admissions API at <code>api.jtreehealth.com</code>. From there it is delivered to (a) our CRM and (b) our admissions team's inbox. The public website itself never stores your submission.</p>

      <h2 class="jth-h3 jth-prose-page__h">HIPAA and clinical records</h2>
      <p>The inquiry form is not protected health information (PHI) under HIPAA &mdash; it's an intake request, not a clinical record. Once your teen is in our care, separate HIPAA-protected systems hold their clinical record. That system is described in the Notice of Privacy Practices you receive at admission.</p>

      <h2 class="jth-h3 jth-prose-page__h">Your rights</h2>
      <p>You can ask us to delete your inquiry data at any time before your teen is admitted. After admission, clinical-record retention is governed by NC and federal law (typically 10 years for adult records and 12 years past majority for minors).</p>

      <h2 class="jth-h3 jth-prose-page__h">Contact</h2>
      <p>Questions about your data? Call <a href="tel:9193355053">(919) 335-5053</a> or email <a href="mailto:privacy@jtreehealth.com">privacy@jtreehealth.com</a>.</p>

    </div>
  </section>

</main>

<?php get_footer(); ?>
