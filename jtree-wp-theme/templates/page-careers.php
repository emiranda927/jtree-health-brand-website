<?php
/**
 * Template Name: Careers
 *
 * NOTE FOR FOUNDER: All body copy and the openings list below are scaffolds —
 * marked [DRAFT] inline. Replace before publishing. The form, schema, and
 * Resend wiring are production-ready; only the words need a real pass.
 *
 * @package JTreeHealth
 */
defined('ABSPATH') || exit;
get_header();

// [DRAFT — founder edit]
$openings = array(
    array(
        'role'       => 'Therapist — PHP / IOP',
        'commitment' => 'Full-time · Apex, NC · LCSW / LCMHC / LMFT',
        'summary'    => 'Lead DBT skills groups and individual therapy for adolescents 10–17 in our PHP and IOP programs.',
    ),
    array(
        'role'       => 'BCBA / Clinical Lead',
        'commitment' => 'Full-time · Apex, NC',
        'summary'    => 'Clinical oversight, supervision of therapy team, treatment-plan review.',
    ),
    array(
        'role'       => 'Open application',
        'commitment' => 'Tell us about you',
        'summary'    => "Don't see your role? We'd still like to hear from you.",
    ),
);
?>

<main id="main">

  <section class="section" style="padding-top: 64px; padding-bottom: 24px;">
    <div class="container" style="max-width: 880px;">
      <span class="jth-eyebrow" style="display:inline-block; margin-bottom: 12px;">Careers</span>
      <h1 class="jth-display-l" style="font-size: clamp(40px, 5vw, 56px); margin: 0 0 20px;">Build something <em class="jth-emph">honest</em> with us.</h1>
      <p class="jth-body-l" style="margin: 0; max-width: 60ch;">
        <!-- [DRAFT — founder edit] -->
        We're building a small adolescent mental-health program in Apex, NC, and we're hiring people who care more about teens than they do about census. If that sounds like you, scroll down.
      </p>
    </div>
  </section>

  <section class="section section-bg-cream-2">
    <div class="container" style="max-width: 880px;">
      <h2 class="jth-h2" style="margin: 0 0 24px;">Open roles</h2>
      <div class="jth-careers-list">
        <?php foreach ($openings as $i => $job) : ?>
          <article class="jth-career-card">
            <div class="jth-career-card__head">
              <h3 class="jth-h4" style="margin:0;"><?php echo esc_html($job['role']); ?></h3>
              <p class="jth-meta" style="margin: 6px 0 0;"><?php echo esc_html($job['commitment']); ?></p>
            </div>
            <p style="margin: 12px 0 0;"><?php echo esc_html($job['summary']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section" id="apply">
    <div class="container" style="max-width: 720px;">
      <span class="jth-eyebrow" style="display:inline-block; margin-bottom: 12px;">Apply</span>
      <h2 class="jth-h2" style="margin: 0 0 16px;">Tell us about you</h2>
      <p class="jth-body-l" style="margin: 0 0 32px; max-width: 60ch; color: var(--jth-fg-muted);">
        <!-- [DRAFT — founder edit] -->
        Short and honest beats long and polished. Send us your resume or LinkedIn and a few words about what brings you here.
      </p>

      <?php if (function_exists('jtree_render_careers_form')) jtree_render_careers_form(); ?>

      <div id="careers-thankyou" class="jth-careers-thanks" hidden>
        <h2 class="jth-h2" style="margin: 0 0 12px;">Thanks — we'll be in touch.</h2>
        <p class="jth-body-l" style="margin: 0; max-width: 56ch;">
          We read every application personally. You'll hear from us within a week.
        </p>
      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>
