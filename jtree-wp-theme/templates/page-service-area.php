<?php
/**
 * Template Name: Service Area
 *
 * Shared template for geo-targeted landing pages (e.g. "Teen IOP in Cary, NC").
 * The page title becomes the H1; the WP editor content renders as the
 * metro-specific middle section. Standard top/bottom blocks are baked in.
 *
 * Founder workflow:
 *   1. Create a WP page titled "Teen IOP in <Metro>, NC".
 *   2. Set Page Attributes → Template → Service Area.
 *   3. (Optional) Set parent page to "Service Areas" so the URL becomes
 *      /service-areas/teen-iop-<metro>/.
 *   4. In the editor, write 200-400 words on what's specific to that metro:
 *      drive time, schools we work with, why a family there might consider
 *      us. Section headings render through the parent-guide prose styling.
 *
 * SEO note: don't duplicate the H1 inside the editor content — it's already
 * rendered above the editor block. Start the body with an H2.
 *
 * @package JTreeHealth
 */
defined('ABSPATH') || exit;
get_header();

$post_id   = get_queried_object_id();
$page_meta = get_post_meta($post_id);

// Optional custom fields the founder can set in WP admin (Custom Fields):
//   metro_name        — short metro label (e.g., "Cary")
//   travel_context    — short blurb (e.g., "20 minutes from Cary along NC-540")
//
// If absent, the template falls back to the page title and a generic line.
$metro_name = !empty($page_meta['metro_name'][0]) ? $page_meta['metro_name'][0] : '';
$travel     = !empty($page_meta['travel_context'][0])
    ? $page_meta['travel_context'][0]
    : 'Our Apex, NC clinic is reachable from most of the western Triangle without highway-hour traffic.';
?>

<main id="main">

  <section class="section section--intro">
    <div class="container container--narrow">
      <span class="jth-eyebrow">Service area</span>
      <h1 class="jth-display-l jth-display-l--sm"><?php the_title(); ?></h1>
      <p class="jth-body-l jth-lede">
        <?php echo esc_html($travel); ?> Adolescent PHP and IOP for ages 10&ndash;17, in network with the major Triangle insurance plans.
      </p>
    </div>
  </section>

  <!-- Metro-specific middle: edited in the WP page editor. Founder writes
       drive context, school partners, local concerns. -->
  <section class="section section-bg-cream-2">
    <div class="container container--narrow">
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="jth-prose">
          <?php the_content(); ?>
        </div>
      <?php endwhile; endif; ?>
    </div>
  </section>

  <!-- Standardized "what we offer" — reads as a summary, links to the
       full programs / insurance pages for depth. -->
  <section class="section">
    <div class="container container--page">
      <h2 class="jth-h2 jth-mb-6">What's available <?php echo $metro_name ? 'for ' . esc_html($metro_name) . ' families' : 'here'; ?></h2>
      <div class="jth-hub-grid">
        <a class="jth-hub-tile" href="<?php echo esc_url(home_url('/programs/#php')); ?>">
          <span class="jth-hub-tile__eyebrow">PHP</span>
          <h3 class="jth-h4 jth-hub-tile__title">Partial hospitalization</h3>
          <p class="jth-hub-tile__body">Mon&ndash;Fri, 9 a.m. to 3 p.m. Two DBT skills groups, one process group, individual + family therapy, academic support.</p>
          <span class="jth-hub-tile__meta">See PHP details &nbsp;&rarr;</span>
        </a>
        <a class="jth-hub-tile" href="<?php echo esc_url(home_url('/programs/#iop')); ?>">
          <span class="jth-hub-tile__eyebrow">IOP</span>
          <h3 class="jth-h4 jth-hub-tile__title">Intensive outpatient</h3>
          <p class="jth-hub-tile__body">In-person + virtual group blocks across the week (Mon, Tue, Thu, Sat). Skills group, individual therapy, family sessions.</p>
          <span class="jth-hub-tile__meta">See IOP details &nbsp;&rarr;</span>
        </a>
        <a class="jth-hub-tile" href="<?php echo esc_url(home_url('/insurance/')); ?>">
          <span class="jth-hub-tile__eyebrow">Insurance</span>
          <h3 class="jth-h4 jth-hub-tile__title">In-network coverage</h3>
          <p class="jth-hub-tile__body">BCBS NC, Cigna / Evernorth, Aetna, and Tricare. We verify before day one.</p>
          <span class="jth-hub-tile__meta">See insurance details &nbsp;&rarr;</span>
        </a>
        <a class="jth-hub-tile" href="<?php echo esc_url(home_url('/what-we-treat/')); ?>">
          <span class="jth-hub-tile__eyebrow">Conditions</span>
          <h3 class="jth-h4 jth-hub-tile__title">What we treat</h3>
          <p class="jth-hub-tile__body">Anxiety, depression, OCD, ADHD with emotion dysregulation, trauma, self-harm, and co-occurring concerns.</p>
          <span class="jth-hub-tile__meta">See full list &nbsp;&rarr;</span>
        </a>
      </div>
    </div>
  </section>

  <section class="cta-band">
    <div class="container">
      <h2>Ready when you are.</h2>
      <p>A clinician will call you back the same business day.</p>
      <a class="jth-btn jth-btn-secondary jth-btn-lg" href="<?php echo esc_url(home_url('/admissions/')); ?>">Start the Conversation</a>
    </div>
  </section>

</main>

<?php get_footer(); ?>
