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
    <img class="collage" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/collage-yellow-scribble.png'); ?>" alt="" style="right:24px; top:30px; width: 220px; opacity: 0.85;">
    <img class="twinkle" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/twinkle-plus-lavender.svg'); ?>" alt="" style="left:8%; top:30%; width:32px;">
    <img class="twinkle" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/twinkle-outline-yellow.svg'); ?>" alt="" style="right:18%; bottom:10%; width:28px;">
    <div class="container">
      <span class="jth-eyebrow">A page for you</span>
      <h1>It's okay to not be okay.</h1>
      <p class="jth-body-l" style="margin-top:14px; max-width: 56ch;">If you're reading this, someone — or something — pointed you here. Maybe a parent. Maybe a school counselor. Maybe a feeling you can't quite name. We're glad you're here.</p>
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

  <section class="section">
    <div class="container" style="max-width: 720px;">
      <h2 class="jth-h2" style="margin: 0 0 24px;">If you want to talk.</h2>
      <p class="jth-body-l" style="margin: 0 0 24px;">No pressure. No big form. You can text or call, or have a parent reach out for you. We'll figure it out from there.</p>
      <div style="display:flex; gap: 12px; flex-wrap: wrap;">
        <a class="jth-btn jth-btn-primary jth-btn-lg" href="tel:9192764005">Call (919) 276-4005</a>
        <a class="jth-btn jth-btn-secondary jth-btn-lg" href="<?php echo esc_url(home_url('/admissions/')); ?>">Have a parent fill out the form</a>
      </div>
      <p class="jth-body-s" style="margin: 32px 0 0;">If you're in a crisis right now — meaning you're thinking about hurting yourself or you don't feel safe — please call or text <strong>988</strong>. They are kind, they are fast, and they answer the phone.</p>
    </div>
  </section>

</main>

<?php get_footer(); ?>
