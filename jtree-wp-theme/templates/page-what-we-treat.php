<?php
/**
 * Template Name: What We Treat
 *
 * @package JTreeHealth
 */
defined('ABSPATH') || exit;
get_header();

$conditions = array(
    array('id' => 'anxiety',     'name' => 'Anxiety + panic',         'desc' => 'Generalized anxiety, panic disorder, social anxiety, school avoidance.'),
    array('id' => 'depression',  'name' => 'Depression',              'desc' => 'Major depression, persistent low mood, hopelessness, isolation.'),
    array('id' => 'ocd',         'name' => 'OCD',                     'desc' => 'Obsessive thoughts and compulsive behaviors. Exposure-based treatment.'),
    array('id' => 'adhd',        'name' => 'ADHD + emotion dysregulation', 'desc' => 'When ADHD shows up alongside anxiety, depression, or self-regulation struggles.'),
    array('id' => 'trauma',      'name' => 'Trauma and PTSD',         'desc' => 'Acute and complex trauma. Trauma-informed throughout, with specialty referrals when needed.'),
    array('id' => 'self-harm',   'name' => 'Self-harm',               'desc' => 'Cutting and other self-injurious behavior. Skills-based approach, no shame.'),
    array('id' => 'co-occurring','name' => 'Co-occurring concerns',   'desc' => 'Most teens we see are managing more than one of the above at once. We work with the whole picture.'),
);
?>

<main id="main">

  <section class="section" style="padding-top: 64px; padding-bottom: 24px;">
    <div class="container" style="max-width: 880px;">
      <span class="jth-eyebrow" style="display:inline-block; margin-bottom: 12px;">What we treat</span>
      <h1 class="jth-display-l" style="font-size: clamp(40px, 5vw, 56px); margin: 0 0 20px;">Real teen mental-health concerns, <em class="jth-emph">treated seriously.</em></h1>
      <p class="jth-body-l" style="margin: 0; max-width: 60ch;">We work with adolescents 10&ndash;17 navigating the conditions below. We do not treat substance use as a primary diagnosis or active eating disorders that require medical stabilization &mdash; we'll point you to the right place if that's what your teen needs.</p>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="cards-3 cards-3--two-up">
        <?php foreach ($conditions as $c) : ?>
          <article class="program-card" id="<?php echo esc_attr($c['id']); ?>">
            <h2 class="jth-h3" style="margin: 0;"><?php echo esc_html($c['name']); ?></h2>
            <p style="margin: 0;"><?php echo esc_html($c['desc']); ?></p>
            <a class="arrow" href="<?php echo esc_url(home_url('/admissions/')); ?>">Talk to admissions &nbsp;&rarr;</a>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <?php
    set_query_var('faq_slug', 'what-we-treat');
    set_query_var('faq_eyebrow', 'Common questions');
    set_query_var('faq_title', 'What families ask first');
    set_query_var('faq_intro', '');
    get_template_part('templates/partials/faq');
  ?>

  <section class="cta-band">
    <div class="container">
      <h2>Not sure if we're the right fit?</h2>
      <p>Tell us a little. We'll talk through it together &mdash; and recommend a different program if that's what your teen needs.</p>
      <a class="jth-btn jth-btn-secondary jth-btn-lg" href="<?php echo esc_url(home_url('/admissions/')); ?>">Start the Conversation</a>
    </div>
  </section>

</main>

<?php get_footer(); ?>
