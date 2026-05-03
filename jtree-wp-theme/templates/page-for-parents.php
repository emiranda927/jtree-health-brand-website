<?php
/**
 * Template Name: For Parents
 * Description: Resources and information for parents
 *
 * @package JTreeHealth
 */

get_header();
?>

<?php get_template_part('templates/partials/crisis-bar'); ?>

<!-- Page Header -->
<section class="jtree-page-header jtree-section--warm-sand jtree-page-header--with-motif">
  <div class="jtree-container">
    <span class="jtree-hero__badge">For Parents</span>
    <h1>You&rsquo;re not failing. You&rsquo;re looking for help.</h1>
    <p class="jtree-lead">Choosing a higher level of care for your teen is one of the hardest decisions a parent can make. Here&rsquo;s what you need to know.</p>
  </div>
</section>

<!-- Family-Centered Healing -->
<section class="jtree-section jtree-section--pale-lavender">
  <div class="jtree-container">
    <div class="jtree-two-col">
      <div>
        <h2>Family-centered healing.</h2>
        <p>At JTree, you&rsquo;re not on the sidelines. Parents and families are an essential part of treatment. We include family therapy, parent education, and regular updates because we know recovery doesn&rsquo;t happen in isolation.</p>
        <p>You know your teen better than anyone. We listen to you, learn from you, and build a treatment plan that works for your whole family.</p>
      </div>
      <div class="jtree-text-center">
        <div class="jtree-blob" style="background-color: var(--jtree-pale-sage); aspect-ratio: 1; display: flex; align-items: center; justify-content: center; padding: 3rem;">
          <div class="jtree-quote">
            <p class="jtree-quote__text" style="font-size: 1.125rem;">I didn&rsquo;t know if this was the right level of care. It was.</p>
            <p class="jtree-quote__attribution">&mdash; Parent of JTree graduate</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- What to Expect -->
<section class="jtree-section jtree-section--pale-sage">
  <div class="jtree-container">
    <div class="jtree-container--narrow" style="margin: 0 auto;">
      <h2>What to expect as a parent.</h2>
      <ul>
        <li><strong>Intake assessment:</strong> A comprehensive conversation with you and your teen. Free, no obligation. We determine the right level of care together.</li>
        <li><strong>Regular updates:</strong> Your teen&rsquo;s treatment team communicates with you throughout the program. No surprises.</li>
        <li><strong>Family therapy:</strong> Weekly (PHP) or biweekly (IOP) family sessions are part of the program.</li>
        <li><strong>Parent education:</strong> We teach you the same skills your teen is learning &mdash; DBT, communication strategies, and crisis planning.</li>
        <li><strong>School coordination:</strong> We work directly with your teen&rsquo;s school to manage absences, accommodations, and transition back.</li>
        <li><strong>Discharge planning:</strong> Before your teen completes the program, we build a plan for what comes next &mdash; including outpatient referrals, coping strategies, and family check-ins.</li>
      </ul>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="jtree-section jtree-section--warm-sand">
  <div class="jtree-container">
    <div class="jtree-section-header">
      <h2>Frequently Asked Questions</h2>
    </div>
    <div class="jtree-container--narrow" style="margin: 0 auto;">

      <div class="jtree-faq__item">
        <button class="jtree-faq__question" onclick="this.parentElement.classList.toggle('is-open')">
          How do I know if my teen needs PHP or IOP?
        </button>
        <div class="jtree-faq__answer">
          <p>That&rsquo;s what the free intake assessment is for. In general, PHP is for teens whose symptoms significantly interfere with daily functioning &mdash; they may be missing school, struggling with safety, or not responding to weekly therapy. IOP is for teens stepping down from PHP or those who need more than weekly therapy but can still attend school.</p>
        </div>
      </div>

      <div class="jtree-faq__item">
        <button class="jtree-faq__question" onclick="this.parentElement.classList.toggle('is-open')">
          Will my teen still go to school?
        </button>
        <div class="jtree-faq__answer">
          <p>PHP runs during school hours (9am&ndash;3pm), so teens in PHP do not attend school during the program. We provide academic support and coordinate directly with schools. IOP runs after school (3&ndash;6pm), so teens in IOP continue attending school.</p>
        </div>
      </div>

      <div class="jtree-faq__item">
        <button class="jtree-faq__question" onclick="this.parentElement.classList.toggle('is-open')">
          How long does the program last?
        </button>
        <div class="jtree-faq__answer">
          <p>PHP typically runs 4&ndash;6 weeks. IOP typically runs 6&ndash;8 weeks. Length of treatment depends on your teen&rsquo;s progress and clinical needs. We reassess regularly.</p>
        </div>
      </div>

      <div class="jtree-faq__item">
        <button class="jtree-faq__question" onclick="this.parentElement.classList.toggle('is-open')">
          Do you accept insurance?
        </button>
        <div class="jtree-faq__answer">
          <p>Yes. We&rsquo;re in-network with BCBS, Cigna/Evernorth, Aetna, UHC/Optum, and Tricare. We handle insurance verification for you. <a href="/insurance/">Learn more about insurance</a>.</p>
        </div>
      </div>

      <div class="jtree-faq__item">
        <button class="jtree-faq__question" onclick="this.parentElement.classList.toggle('is-open')">
          What if my teen doesn&rsquo;t want to go?
        </button>
        <div class="jtree-faq__answer">
          <p>That&rsquo;s normal. Most teens are resistant at first. Our clinicians are experienced in building rapport with reluctant adolescents. We also work with you on motivational strategies. Many teens who start reluctantly end up grateful for the experience.</p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- CTA -->
<section class="jtree-section jtree-section--forest">
  <div class="jtree-container jtree-text-center">
    <h2>Ready to take the next step?</h2>
    <p>The intake assessment is free and there&rsquo;s no obligation. We&rsquo;ll help you figure out what&rsquo;s right for your family.</p>
    <a href="/admissions/" class="jtree-btn jtree-btn--white jtree-btn--lg">Start the Conversation</a>
  </div>
</section>

<?php get_footer(); ?>
