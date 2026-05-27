<?php
/**
 * Template Name: For Teens
 *
 * @package JTreeHealth
 */
defined('ABSPATH') || exit;
get_header();

?>

<main id="main">

  <section class="teen-hero teen-hero--composed">
    <img class="collage teen-hero__collage" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/collage-yellow-scribble.png'); ?>" alt="" width="320" height="160" loading="lazy" decoding="async" aria-hidden="true">
    <img class="twinkle teen-hero__twinkle teen-hero__twinkle--a" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/twinkle-plus-lavender.svg'); ?>" alt="" width="32" height="32" loading="lazy" aria-hidden="true">
    <img class="twinkle teen-hero__twinkle teen-hero__twinkle--b" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/twinkle-outline-yellow.svg'); ?>" alt="" width="28" height="28" loading="lazy" aria-hidden="true">
    <div class="container container--tight">
      <span class="jth-eyebrow jth-reveal">A page for you</span>
      <h1 class="jth-reveal jth-reveal--delay-1">It's okay to not be okay.</h1>
      <p class="jth-body-l jth-mt-4 jth-reveal jth-reveal--delay-2">If you're reading this, someone — or something — pointed you here. Maybe a parent. Maybe a school counselor. Maybe a feeling you can't quite name. We're glad you're here.</p>
      <p class="jth-hand jth-hand--tape-strong jth-reveal jth-reveal--delay-3">You're not alone in this.</p>
    </div>
  </section>

  <section class="section">
    <div class="container container--tight">
      <h2 class="jth-h2 jth-mb-4">What we are.</h2>
      <p class="jth-body-l jth-mb-4">We're a place where teens come during the day to work on really hard stuff — anxiety, depression, panic, self-harm, the kind of thoughts that don't go away. We're not a hospital. You go home at night.</p>
      <p class="jth-body-l jth-mb-4">You'll be in groups with other teens. You'll have a therapist. You'll learn skills you can actually use, like how to come down from a panic spiral or how to ride out an urge.</p>
      <p class="jth-body-l">We're not going to tell you everything will be fine. We're going to help you build the skills to get through the times when it isn't.</p>
    </div>
  </section>

  <section class="section section-bg-cream-2">
    <div class="container container--tight">
      <h2 class="jth-h2 jth-mb-4">What we're not.</h2>
      <ul class="jth-list">
        <li>We're not your parents. What you say in therapy stays in therapy, with the legal exceptions every clinician has.</li>
        <li>We're not going to lecture you about phones, or sleep, or your friends, unless you want to talk about it.</li>
        <li>We're not going to make you talk if you don't want to. Just showing up is enough on day one.</li>
        <li>We're not going to pretend the world isn't hard right now.</li>
      </ul>
    </div>
  </section>

  <section class="section section-spacious section-bg-pale-lav">
    <div class="container container--tight jth-text-center">
      <blockquote class="jth-page-quote jth-reveal">You are not broken. <em>Your nervous system is trying to protect you.</em></blockquote>
      <p class="jth-body-s jth-text-muted jth-reveal jth-reveal--delay-1">&mdash; The first thing we teach in IOP, Day 1.</p>
    </div>
  </section>

  <section class="section">
    <div class="container container--tight">
      <h2 class="jth-h2 jth-mb-4">What 12 weeks looks like for you.</h2>
      <p class="jth-body-l jth-mb-4">IOP runs for 12 weeks. You'll pick three group blocks each week from a rotation that includes in-person afternoons in Apex (Mon, Tue, Thu) and virtual mornings from home (Tue, Thu, Sat). Wednesday and Friday are individual + family days. Every session has the same shape so you know what's coming next: a check-in, some quiet mindfulness, learning a new skill, practicing it, processing what came up, and a check-out.</p>
      <p class="jth-body-l jth-mb-4">The skills build on each other:</p>
      <ul class="jth-list jth-list--sm">
        <li><strong>First, how your body works.</strong> Why panic feels the way it does. Why shutting down isn't a choice. Why your brain reacts before you can think.</li>
        <li><strong>Then, what to do in the moment.</strong> How to come down from a panic spiral. How to ride out an urge instead of acting on it.</li>
        <li><strong>Then, how to handle the feelings before they get huge.</strong> Catching them earlier. Sleep, food, and movement as actual tools, not nagging.</li>
        <li><strong>Then, how to be in relationships without losing yourself.</strong> Asking for what you need. Saying no. Repairing a friendship after a fight.</li>
        <li><strong>Finally, how to deal with the people at home.</strong> Including how to stay calm when a parent isn't. (We work with them too.)</li>
      </ul>
      <p class="jth-body-l">By the end, you'll have written yourself a regulation plan and a letter to the future you. Whatever happens next, you'll have a map.</p>
    </div>
  </section>

  <section class="section section-bg-cream-2">
    <div class="container container--tight">
      <h2 class="jth-h2 jth-mb-6">If you want to talk.</h2>
      <p class="jth-body-l jth-mb-6">No pressure. No big form. You can text or call, or have a parent reach out for you. We'll figure it out from there.</p>
      <div class="jth-row-buttons">
        <a class="jth-btn jth-btn-lime jth-btn-lg" href="tel:9193355053">Call (919) 335-5053</a>
        <a class="jth-btn jth-btn-secondary jth-btn-lg" href="<?php echo esc_url(home_url('/admissions/')); ?>">Have a parent fill out the form</a>
      </div>
      <p class="jth-body-s jth-mt-8">If you're in a crisis right now — meaning you're thinking about hurting yourself or you don't feel safe — please call or text <strong>988</strong>. They are kind, they are fast, and they answer the phone.</p>
    </div>
  </section>

</main>

<?php get_footer(); ?>
