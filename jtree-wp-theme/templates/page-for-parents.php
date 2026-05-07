<?php
/**
 * Template Name: For Parents
 *
 * @package JTreeHealth
 */
defined('ABSPATH') || exit;
get_header();

/**
 * Parent-hub guide tiles. Each guide is a separate WP page assigned the
 * "Parent Guide" template. Slugs assume `/for-parents/<slug>/` — this only
 * works if the WP "for-parents" page is set as the parent of each guide
 * page in the WP admin (Page Attributes → Parent → For Parents).
 *
 * [DRAFT — founder edit] — replace titles, summaries, and read times once
 * the guide pages are written.
 */
$home   = home_url('/');
$guides = array(
    array(
        'slug'  => 'for-parents/is-this-a-crisis',
        'eyebrow' => 'When to act now',
        'title' => 'Is this a crisis, or are we close?',
        'desc'  => 'Signs that say "call 988 tonight" vs. signs that say "schedule an evaluation this week."',
        'time'  => '4 min read',
    ),
    array(
        'slug'  => 'for-parents/php-vs-iop',
        'eyebrow' => 'Levels of care',
        'title' => "PHP, IOP, and what's right for your teen",
        'desc'  => "What 'partial hospitalization' actually means, and how it compares to weekly therapy or IOP.",
        'time'  => '5 min read',
    ),
    array(
        'slug'  => 'for-parents/insurance-and-cost',
        'eyebrow' => 'Money + coverage',
        'title' => 'Insurance, deductibles, and the real cost',
        'desc'  => 'What we verify before day one, what counts toward your deductible, and what to ask your plan.',
        'time'  => '6 min read',
    ),
    array(
        'slug'  => 'for-parents/what-the-first-call-is-like',
        'eyebrow' => 'The first call',
        'title' => "What our first call is actually like",
        'desc'  => "No script. A clinician asking what's going on, and listening. About 25 minutes.",
        'time'  => '3 min read',
    ),
);
?>

<main id="main">

  <section class="section" style="padding-top: 64px; padding-bottom: 24px;">
    <div class="container" style="max-width: 880px;">
      <span class="jth-eyebrow" style="display:inline-block; margin-bottom: 12px;">For parents and caregivers</span>
      <h1 class="jth-display-l" style="font-size: clamp(40px, 5vw, 56px); margin: 0 0 20px;">You don't have to figure this out <em class="jth-emph">alone.</em></h1>
      <p class="jth-body-l" style="margin: 0; max-width: 60ch;">Most families come to us already exhausted. Outpatient therapy isn't holding. School is harder than it should be. The teen you love is still struggling, and you're running out of ideas.</p>
    </div>
  </section>

  <section class="section">
    <div class="container" style="max-width: 880px;">
      <h2 class="jth-h2" style="margin: 0 0 16px;">What to expect from us</h2>
      <ul style="font-size: 18px; line-height: 1.7; padding-left: 20px; margin: 0 0 24px;">
        <li><strong>One business day to a real conversation.</strong> An admissions clinician calls you &mdash; not a sales person, not a scheduler.</li>
        <li><strong>No surprise costs.</strong> We verify insurance before your teen's first day. If we're not in network with your plan, we'll tell you up front.</li>
        <li><strong>You stay in the loop.</strong> Family sessions are part of every program, not an add-on.</li>
        <li><strong>Honesty about fit.</strong> If we're not the right level of care, we'll tell you who is.</li>
      </ul>
      <p class="jth-body" style="margin: 0 0 28px;">If you're closer to a crisis than a question, call <strong>988</strong> right now. They are kind, fast, and they answer. We'll be here when you're ready.</p>
      <a class="jth-btn jth-btn-primary jth-btn-lg" href="<?php echo esc_url(home_url('/admissions/')); ?>">Start the Conversation</a>
    </div>
  </section>

  <section class="section section-bg-cream-2" id="resources">
    <div class="container" style="max-width: 1080px;">
      <span class="jth-eyebrow" style="display:inline-block; margin-bottom: 12px;">Plain-language guides</span>
      <h2 class="jth-h2" style="margin: 0 0 12px;">Things parents tell us they wish they'd known sooner</h2>
      <p class="jth-body-l" style="margin: 0 0 32px; max-width: 60ch; color: var(--jth-fg-muted);">No gates. No PDF emails. Read what's useful, skip what isn't.</p>

      <div class="jth-hub-grid">
        <?php foreach ($guides as $g) : ?>
          <a class="jth-hub-tile" href="<?php echo esc_url($home . $g['slug'] . '/'); ?>">
            <span class="jth-hub-tile__eyebrow"><?php echo esc_html($g['eyebrow']); ?></span>
            <h3 class="jth-h4" style="margin:8px 0 10px;"><?php echo esc_html($g['title']); ?></h3>
            <p style="margin: 0 0 14px;"><?php echo esc_html($g['desc']); ?></p>
            <span class="jth-hub-tile__meta"><?php echo esc_html($g['time']); ?> &nbsp;&rarr;</span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="cta-band">
    <div class="container">
      <h2>Questions before you fill out a form?</h2>
      <p>Call us. A clinician will pick up &mdash; or call you back the same business day.</p>
      <a class="jth-btn jth-btn-secondary jth-btn-lg" href="tel:9192764005">(919) 276-4005</a>
    </div>
  </section>

</main>

<?php get_footer(); ?>
