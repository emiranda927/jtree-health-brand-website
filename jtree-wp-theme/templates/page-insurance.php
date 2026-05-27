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

  <section class="section section--intro">
    <div class="container container--narrow">
      <span class="jth-eyebrow">Insurance</span>
      <h1 class="jth-display-l">In-network coverage, <em class="jth-emph">verified before day one.</em></h1>
      <p class="jth-body-l jth-lede">We hold contracts with the major commercial plans serving the Triangle. We verify your specific benefits before your teen's first day, so the cost question is answered before treatment starts.</p>
    </div>
  </section>

  <section class="section section-bg-cream-2">
    <div class="container container--narrow">
      <h2 class="jth-h2 jth-mb-5">Plans we're in network with</h2>
      <div class="jth-row-pills">
        <span class="jth-pill jth-pill-sage">BlueCross BlueShield (BCBS NC)</span>
        <span class="jth-pill jth-pill-sage">Cigna / Evernorth</span>
        <span class="jth-pill jth-pill-sage">Aetna</span>
        <span class="jth-pill jth-pill-sage">Tricare</span>
      </div>
      <p class="jth-body-s jth-mt-6">Don't see your plan? Call us &mdash; we sometimes accept out-of-network on a case-by-case basis, and we can help you understand your single-case-agreement options.</p>
    </div>
  </section>

  <section class="section">
    <div class="container container--narrow">
      <h2 class="jth-h2 jth-mb-4">How verification works</h2>
      <ol class="jth-list">
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
