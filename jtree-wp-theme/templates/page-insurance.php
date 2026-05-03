<?php
/**
 * Template Name: Insurance
 * Description: Insurance information and verification
 *
 * @package JTreeHealth
 */

get_header();
?>

<?php get_template_part('templates/partials/crisis-bar'); ?>

<!-- Page Header -->
<section class="jtree-page-header jtree-section--warm-sand jtree-page-header--with-motif">
  <div class="jtree-container">
    <span class="jtree-hero__badge">Insurance &amp; Costs</span>
    <h1>We handle verification for you.</h1>
    <p class="jtree-lead">We&rsquo;re in-network with most major insurance carriers. Tell us your plan and we&rsquo;ll figure out the rest.</p>
  </div>
</section>

<!-- In-Network Carriers -->
<section class="jtree-section jtree-section--pale-lavender">
  <div class="jtree-container">
    <div class="jtree-section-header">
      <h2>In-Network Insurance</h2>
      <p>We accept the following insurance plans for our PHP and IOP programs:</p>
    </div>
    <div class="jtree-insurance-grid">
      <div class="jtree-insurance-card">
        <h3>BCBS</h3>
        <p>Blue Cross Blue Shield</p>
      </div>
      <div class="jtree-insurance-card">
        <h3>Cigna / Evernorth</h3>
        <p>Cigna and Evernorth plans</p>
      </div>
      <div class="jtree-insurance-card">
        <h3>Aetna</h3>
        <p>Aetna plans</p>
      </div>
      <div class="jtree-insurance-card">
        <h3>UHC / Optum</h3>
        <p>UnitedHealthcare and Optum</p>
      </div>
      <div class="jtree-insurance-card">
        <h3>Tricare</h3>
        <p>All Tricare plans</p>
      </div>
    </div>
  </div>
</section>

<!-- How It Works -->
<section class="jtree-section jtree-section--pale-sage">
  <div class="jtree-container">
    <div class="jtree-container--narrow" style="margin: 0 auto;">
      <h2>How insurance verification works.</h2>
      <div class="jtree-steps">
        <div class="jtree-step">
          <div class="jtree-step__number">1</div>
          <div class="jtree-step__content">
            <h3>Tell us your plan</h3>
            <p>During your initial call or form submission, share your insurance carrier and member ID.</p>
          </div>
        </div>
        <div class="jtree-step">
          <div class="jtree-step__number">2</div>
          <div class="jtree-step__content">
            <h3>We verify benefits</h3>
            <p>Our team contacts your insurance company to verify coverage for PHP or IOP. We do this for you &mdash; you don&rsquo;t have to make any calls.</p>
          </div>
        </div>
        <div class="jtree-step">
          <div class="jtree-step__number">3</div>
          <div class="jtree-step__content">
            <h3>We explain your costs</h3>
            <p>Before treatment begins, we&rsquo;ll clearly explain what&rsquo;s covered, what your copay or coinsurance will be, and whether there&rsquo;s a deductible to meet.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Additional Info -->
<section class="jtree-section jtree-section--warm-sand">
  <div class="jtree-container">
    <div class="jtree-container--narrow" style="margin: 0 auto;">
      <h2>Don&rsquo;t see your insurance?</h2>
      <p>If your carrier isn&rsquo;t listed, we may still be able to help. Some plans offer out-of-network benefits for PHP and IOP programs. Call us and we&rsquo;ll help you explore your options.</p>
      <p>We believe cost should never be the reason a teen doesn&rsquo;t get the help they need. If you have questions about affordability, talk to us.</p>
      <div class="jtree-mt-xl">
        <a href="tel:+19192764005" class="jtree-btn jtree-btn--primary jtree-btn--lg">Call (919) 276-4005</a>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="jtree-section jtree-section--forest">
  <div class="jtree-container jtree-text-center">
    <h2>Ready to verify your insurance?</h2>
    <p>Submit an inquiry and we&rsquo;ll start the verification process right away.</p>
    <a href="/admissions/" class="jtree-btn jtree-btn--white jtree-btn--lg">Start the Conversation</a>
  </div>
</section>

<?php get_footer(); ?>
