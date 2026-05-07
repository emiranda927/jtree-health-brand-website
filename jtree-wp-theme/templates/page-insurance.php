<?php
/**
 * Template Name: Insurance
 *
 * @package JTreeHealth
 */
defined('ABSPATH') || exit;
get_header();
?>

<main id="main">

  <section class="section" style="padding-top: 64px; padding-bottom: 24px;">
    <div class="container" style="max-width: 880px;">
      <span class="jth-eyebrow" style="display:inline-block; margin-bottom: 12px;">Insurance</span>
      <h1 class="jth-display-l" style="font-size: clamp(40px, 5vw, 56px); margin: 0 0 20px;">In-network coverage, <em class="jth-emph">verified before day one.</em></h1>
      <p class="jth-body-l" style="margin: 0; max-width: 60ch;">We hold contracts with the major commercial plans serving the Triangle. We verify your specific benefits before your teen's first day, so the cost question is answered before treatment starts.</p>
    </div>
  </section>

  <section class="section section-bg-cream-2">
    <div class="container" style="max-width: 880px;">
      <h2 class="jth-h2" style="margin: 0 0 20px;">Plans we're in network with</h2>
      <div class="jth-row-pills">
        <span class="jth-pill jth-pill-sage">BlueCross BlueShield (BCBS NC)</span>
        <span class="jth-pill jth-pill-sage">Cigna / Evernorth</span>
        <span class="jth-pill jth-pill-sage">Aetna</span>
        <span class="jth-pill jth-pill-sage">UnitedHealthcare / Optum</span>
        <span class="jth-pill jth-pill-sage">Tricare</span>
        <span class="jth-pill jth-pill-sage">Medicaid (NC)</span>
      </div>
      <p class="jth-body-s" style="margin: 24px 0 0;">Don't see your plan? Call us &mdash; we sometimes accept out-of-network on a case-by-case basis, and we can help you understand your single-case-agreement options.</p>
    </div>
  </section>

  <section class="section">
    <div class="container" style="max-width: 880px;">
      <h2 class="jth-h2" style="margin: 0 0 16px;">How verification works</h2>
      <ol style="font-size: 18px; line-height: 1.7; padding-left: 20px; margin: 0 0 24px;">
        <li><strong>You submit the inquiry form.</strong> We collect your insurance details there or on the follow-up call.</li>
        <li><strong>We verify.</strong> Our admissions team contacts your insurer and confirms PHP/IOP benefits, deductible, and any prior-auth requirement.</li>
        <li><strong>You get a clear estimate.</strong> Before your teen starts, you know what to expect financially.</li>
      </ol>
      <a class="jth-btn jth-btn-primary jth-btn-lg" href="<?php echo esc_url(home_url('/admissions/')); ?>">Verify your coverage</a>
    </div>
  </section>

  <?php
    set_query_var('faq_slug', 'insurance');
    set_query_var('faq_eyebrow', 'Insurance, answered');
    set_query_var('faq_title', 'What parents ask about coverage');
    set_query_var('faq_intro', 'The questions families bring to our admissions team most often.');
    get_template_part('templates/partials/faq');
  ?>

</main>

<?php get_footer(); ?>
