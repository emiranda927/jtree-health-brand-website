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

  <section class="section section--intro-tight">
    <div class="container container--narrow">
      <p class="jth-meta jth-guide-back">
        <a class="jth-guide-back__link" href="<?php echo esc_url($parent_url); ?>">&larr; <?php echo esc_html($parent_title); ?></a>
      </p>
      <span class="jth-eyebrow">Parent guide</span>
      <h1 class="jth-display-l jth-display-l--sm jth-mb-2"><?php the_title(); ?></h1>
    </div>
  </section>

  <section class="section section--continuation">
    <div class="container container--page jth-guide-grid">
      <article class="jth-guide-body">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
          <div class="jth-prose">
            <?php the_content(); ?>
          </div>
        <?php endwhile; endif; ?>

        <p class="jth-meta jth-guide-updated">
          Last updated <?php echo esc_html(get_the_modified_date('F j, Y')); ?>.
        </p>
      </article>

      <aside class="jth-guide-aside">
        <?php if (!empty($siblings)) : ?>
          <div class="jth-guide-aside__block">
            <span class="jth-eyebrow">Related</span>
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
          <p class="jth-guide-aside__lede">When you're ready, we're here.</p>
          <p class="jth-guide-aside__sub">An admissions clinician will call you back within 2 to 4 business hours.</p>
          <a class="jth-btn jth-btn-primary" href="<?php echo esc_url(home_url('/admissions/')); ?>">Start the Conversation</a>
        </div>
      </aside>
    </div>
  </section>

</main>

<?php get_footer(); ?>
