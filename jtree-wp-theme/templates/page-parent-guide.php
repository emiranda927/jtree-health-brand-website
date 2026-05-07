<?php
/**
 * Template Name: Parent Guide
 *
 * Single-guide template used for child pages of /for-parents/. Renders the
 * page editor content in a generous editorial column with a small sidebar
 * (related guides + a low-pressure CTA). Set Page Attributes → Parent → For
 * Parents in WP admin so URLs become /for-parents/<slug>/.
 *
 * @package JTreeHealth
 */
defined('ABSPATH') || exit;
get_header();

$post_id      = get_queried_object_id();
$parent_id    = wp_get_post_parent_id($post_id);
$parent_url   = $parent_id ? get_permalink($parent_id) : home_url('/for-parents/');
$parent_title = $parent_id ? get_the_title($parent_id) : 'For Parents';

// Sibling guides (other children of the same parent), excluding this page.
$siblings = $parent_id
    ? get_pages(array(
        'child_of'    => $parent_id,
        'parent'      => $parent_id,
        'exclude'     => array($post_id),
        'sort_column' => 'menu_order,post_title',
        'number'      => 4,
      ))
    : array();
?>

<main id="main">

  <section class="section" style="padding-top: 56px; padding-bottom: 16px;">
    <div class="container" style="max-width: 880px;">
      <p class="jth-meta" style="margin: 0 0 16px;">
        <a href="<?php echo esc_url($parent_url); ?>" style="color: inherit; text-decoration: none;">&larr; <?php echo esc_html($parent_title); ?></a>
      </p>
      <span class="jth-eyebrow" style="display:inline-block; margin-bottom: 12px;">Parent guide</span>
      <h1 class="jth-display-l" style="font-size: clamp(36px, 4.5vw, 52px); margin: 0 0 8px;"><?php the_title(); ?></h1>
    </div>
  </section>

  <section class="section" style="padding-top: 24px;">
    <div class="container jth-guide-grid" style="max-width: 1080px;">
      <article class="jth-guide-body">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
          <div class="jth-prose">
            <?php the_content(); ?>
          </div>
        <?php endwhile; endif; ?>

        <p class="jth-meta" style="margin-top: 40px; padding-top: 24px; border-top: 1px solid var(--jth-rule);">
          Last updated <?php echo esc_html(get_the_modified_date('F j, Y')); ?>.
        </p>
      </article>

      <aside class="jth-guide-aside">
        <?php if (!empty($siblings)) : ?>
          <div class="jth-guide-aside__block">
            <span class="jth-eyebrow" style="display:inline-block; margin-bottom: 10px;">Related</span>
            <ul class="jth-guide-related">
              <?php foreach ($siblings as $s) : ?>
                <li>
                  <a href="<?php echo esc_url(get_permalink($s->ID)); ?>"><?php echo esc_html(get_the_title($s->ID)); ?></a>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <div class="jth-guide-aside__block jth-guide-aside__cta">
          <p style="margin: 0 0 12px; font-family: var(--font-editorial, 'Fraunces', serif); font-size: 22px; line-height: 1.3; color: var(--jth-deep-green);">When you're ready, we're here.</p>
          <p style="margin: 0 0 18px; color: var(--jth-fg-muted); font-size: 15px; line-height: 1.55;">A clinician — not a sales person — will call you back the same business day.</p>
          <a class="jth-btn jth-btn-primary" href="<?php echo esc_url(home_url('/admissions/')); ?>">Start the Conversation</a>
        </div>
      </aside>
    </div>
  </section>

</main>

<?php get_footer(); ?>
