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

  <section class="teen-hero">
    <img class="collage" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/collage-yellow-scribble.png'); ?>" alt="" width="320" height="160" loading="lazy" decoding="async" aria-hidden="true" style="right:24px; top:30px; width: 220px; opacity: 0.85;">
    <img class="twinkle" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/twinkle-plus-lavender.svg'); ?>" alt="" width="32" height="32" loading="lazy" aria-hidden="true" style="left:8%; top:30%; width:32px;">
    <img class="twinkle" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/twinkle-outline-yellow.svg'); ?>" alt="" width="28" height="28" loading="lazy" aria-hidden="true" style="right:18%; bottom:10%; width:28px;">
    <div class="container" style="max-width: 720px;">
      <span class="jth-eyebrow">A page for you</span>
      <h1>It's okay to not be okay.</h1>
      <p class="jth-body-l" style="margin-top:14px;">If you're reading this, someone — or something — pointed you here. Maybe a parent. Maybe a school counselor. Maybe a feeling you can't quite name. We're glad you're here.</p>
      <p class="jth-hand" style="margin-top: 24px; transform: rotate(-3deg);">You're not alone in this.</p>
    </div>
  </section>

  <section class="section">
    <div class="container" style="max-width: 720px;">
      <h2 class="jth-h2" style="margin: 0 0 16px;">What we are.</h2>
      <p class="jth-body-l" style="margin: 0 0 14px;">We're a place where teens come during the day to work on really hard stuff — anxiety, depression, panic, self-harm, the kind of thoughts that don't go away. We're not a hospital. You go home at night.</p>
      <p class="jth-body-l" style="margin: 0 0 14px;">You'll be in groups with other teens. You'll have a therapist. You'll learn skills you can actually use, like how to come down from a panic spiral or how to ride out an urge.</p>
      <p class="jth-body-l" style="margin: 0;">We're not going to tell you everything will be fine. We're going to help you build the skills to get through the times when it isn't.</p>
    </div>
  </section>

  <section class="section section-bg-cream-2">
    <div class="container" style="max-width: 720px;">
      <h2 class="jth-h2" style="margin: 0 0 16px;">What we're not.</h2>
      <ul style="font-size: 18px; line-height: 1.7; padding-left: 20px; margin: 0;">
        <li>We're not your parents. What you say in therapy stays in therapy, with the legal exceptions every clinician has.</li>
        <li>We're not going to lecture you about phones, or sleep, or your friends, unless you want to talk about it.</li>
        <li>We're not going to make you talk if you don't want to. Just showing up is enough on day one.</li>
        <li>We're not going to pretend the world isn't hard right now.</li>
      </ul>
    </div>
  </section>

  <section class="section section-bg-pale-lav">
    <div class="container" style="max-width: 720px; text-align: center;">
      <blockquote style="font-size: clamp(28px, 4vw, 40px); font-family: var(--jth-serif, Georgia, serif); line-height: 1.25; margin: 0 0 16px;">You are not broken. <em>Your nervous system is trying to protect you.</em></blockquote>
      <p class="jth-body-s" style="margin: 0; color: var(--jth-fg-muted);">&mdash; The first thing we teach in IOP, Day 1.</p>
    </div>
  </section>

  <section class="section">
    <div class="container" style="max-width: 720px;">
      <h2 class="jth-h2" style="margin: 0 0 16px;">What 12 weeks looks like for you.</h2>
      <p class="jth-body-l" style="margin: 0 0 16px;">IOP runs for 12 weeks. You'll pick three group blocks each week from a rotation that includes in-person afternoons in Apex (Mon, Tue, Thu) and virtual mornings from home (Tue, Thu, Sat). Wednesday and Friday are individual + family days. Every session has the same shape so you know what's coming next: a check-in, some quiet mindfulness, learning a new skill, practicing it, processing what came up, and a check-out.</p>
      <p class="jth-body-l" style="margin: 0 0 16px;">The skills build on each other:</p>
      <ul style="font-size: 17px; line-height: 1.7; padding-left: 20px; margin: 0 0 16px;">
        <li><strong>First, how your body works.</strong> Why panic feels the way it does. Why shutting down isn't a choice. Why your brain reacts before you can think.</li>
        <li><strong>Then, what to do in the moment.</strong> How to come down from a panic spiral. How to ride out an urge instead of acting on it.</li>
        <li><strong>Then, how to handle the feelings before they get huge.</strong> Catching them earlier. Sleep, food, and movement as actual tools, not nagging.</li>
        <li><strong>Then, how to be in relationships without losing yourself.</strong> Asking for what you need. Saying no. Repairing a friendship after a fight.</li>
        <li><strong>Finally, how to deal with the people at home.</strong> Including how to stay calm when a parent isn't. (We work with them too.)</li>
      </ul>
      <p class="jth-body-l" style="margin: 0;">By the end, you'll have written yourself a regulation plan and a letter to the future you. Whatever happens next, you'll have a map.</p>
    </div>
  </section>

  <section class="section section-bg-cream-2">
    <div class="container" style="max-width: 720px;">
      <h2 class="jth-h2" style="margin: 0 0 24px;">If you want to talk.</h2>
      <p class="jth-body-l" style="margin: 0 0 24px;">No pressure. No big form. You can text or call, or have a parent reach out for you. We'll figure it out from there.</p>
      <div class="jth-row-buttons">
        <a class="jth-btn jth-btn-lime jth-btn-lg" href="tel:9193355053">Call (919) 335-5053</a>
        <a class="jth-btn jth-btn-secondary jth-btn-lg" href="<?php echo esc_url(home_url('/admissions/')); ?>">Have a parent fill out the form</a>
      </div>
      <p class="jth-body-s" style="margin: 32px 0 0;">If you're in a crisis right now — meaning you're thinking about hurting yourself or you don't feel safe — please call or text <strong>988</strong>. They are kind, they are fast, and they answer the phone.</p>
    </div>
  </section>

</main>

<?php get_footer(); ?>
