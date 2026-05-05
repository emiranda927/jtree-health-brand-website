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

  <section class="section" style="padding-top: 64px; padding-bottom: 24px;">
    <div class="container" style="max-width: 760px;">
      <span class="jth-eyebrow" style="display:inline-block; margin-bottom: 12px;">Privacy policy</span>
      <h1 class="jth-h1" style="margin: 0 0 16px;">Your data, in plain English.</h1>
      <p class="jth-meta" style="margin: 0;">Last updated: <?php echo esc_html(date('F j, Y')); ?></p>
    </div>
  </section>

  <section class="section">
    <div class="container" style="max-width: 760px; line-height: 1.65;">

      <p class="jth-body-l" style="margin: 0 0 20px;"><strong>This page is a placeholder.</strong> A full privacy policy will replace it before launch. The summary below describes what the website does today.</p>

      <h2 class="jth-h3" style="margin: 32px 0 8px;">What we collect on this site</h2>
      <ul style="padding-left: 20px;">
        <li>The fields you submit on the admissions form: name, email, phone, your teen's age, program interest, best time to call, and how you found us.</li>
        <li>Anonymous analytics: pages visited, device type, country (not city). IP addresses are anonymized in our analytics tool.</li>
        <li>Cookies required for the cookie banner to remember your choice. No advertising or tracking cookies without consent.</li>
      </ul>

      <h2 class="jth-h3" style="margin: 32px 0 8px;">What we don't do</h2>
      <ul style="padding-left: 20px;">
        <li>We do not sell your data to anyone.</li>
        <li>We do not use Meta, TikTok, LinkedIn, or other ad-network pixels.</li>
        <li>We do not record your session or capture form-field values in analytics.</li>
        <li>We do not store any clinical detail submitted on the site beyond the inquiry form fields above.</li>
      </ul>

      <h2 class="jth-h3" style="margin: 32px 0 8px;">Where the form data goes</h2>
      <p>When you submit the admissions form, the data is sent directly to our admissions API at <code>api.jtreehealth.com</code>. From there it is delivered to (a) our CRM and (b) our admissions team's inbox via Resend. The website host (WordPress) never stores your submission.</p>

      <h2 class="jth-h3" style="margin: 32px 0 8px;">HIPAA and clinical records</h2>
      <p>The inquiry form is not protected health information (PHI) under HIPAA &mdash; it's an intake request, not a clinical record. Once your teen is in our care, separate HIPAA-protected systems hold their clinical record. That system is described in the Notice of Privacy Practices you receive at admission.</p>

      <h2 class="jth-h3" style="margin: 32px 0 8px;">Your rights</h2>
      <p>You can ask us to delete your inquiry data at any time before your teen is admitted. After admission, clinical-record retention is governed by NC and federal law (typically 10 years for adult records and 12 years past majority for minors).</p>

      <h2 class="jth-h3" style="margin: 32px 0 8px;">Contact</h2>
      <p>Questions about your data? Call <a href="tel:9192764005">(919) 276-4005</a> or email <a href="mailto:privacy@jtreehealth.com">privacy@jtreehealth.com</a>.</p>

    </div>
  </section>

</main>

<?php get_footer(); ?>
