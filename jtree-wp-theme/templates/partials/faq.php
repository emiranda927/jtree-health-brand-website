<?php
/**
 * Partial · FAQ section.
 *
 * Renders a visible Q&A list pulled from jtree_faq_map() (inc/seo.php).
 * The same map drives the FAQPage JSON-LD, so the answer copy here and the
 * schema stay in sync — Google requires the FAQ rich result to be visible.
 *
 * Args (set via set_query_var before get_template_part):
 *   - faq_slug    (string)        page slug whose Q&A to render
 *   - faq_eyebrow (string|null)   short label above the heading
 *   - faq_title   (string|null)   section heading
 *   - faq_intro   (string|null)   one-line intro paragraph
 *
 * @package JTreeHealth
 */
defined('ABSPATH') || exit;

$slug    = (string) get_query_var('faq_slug');
$eyebrow = (string) (get_query_var('faq_eyebrow') ?: 'Common questions');
$title   = (string) (get_query_var('faq_title')   ?: 'Questions families ask');
$intro   = (string) get_query_var('faq_intro');

if (!$slug || !function_exists('jtree_faq_map')) return;
$map = jtree_faq_map();
if (empty($map[$slug])) return;
?>
<section class="section section-bg-cream-2 jth-faq-section" aria-labelledby="faq-<?php echo esc_attr($slug); ?>-title">
  <div class="container container--prose">
    <span class="jth-eyebrow"><?php echo esc_html($eyebrow); ?></span>
    <h2 id="faq-<?php echo esc_attr($slug); ?>-title" class="jth-h2 <?php echo $intro ? 'jth-mb-3' : 'jth-mb-6'; ?>"><?php echo esc_html($title); ?></h2>
    <?php if ($intro) : ?>
      <p class="jth-body-l jth-lede jth-text-muted jth-mb-7"><?php echo esc_html($intro); ?></p>
    <?php endif; ?>

    <div class="jth-faq">
      <?php foreach ($map[$slug] as $i => $qa) : ?>
        <details class="jth-faq__item"<?php echo $i === 0 ? ' open' : ''; ?>>
          <summary class="jth-faq__q">
            <span class="jth-faq__q-text"><?php echo esc_html($qa['q']); ?></span>
            <span class="jth-faq__chevron" aria-hidden="true">
              <svg width="20" height="20" viewBox="0 0 24 24" focusable="false">
                <path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
              </svg>
            </span>
          </summary>
          <div class="jth-faq__a">
            <p><?php echo esc_html($qa['a']); ?></p>
          </div>
        </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>
