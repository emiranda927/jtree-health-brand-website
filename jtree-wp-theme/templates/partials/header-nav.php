<?php
/**
 * Partial · Header + primary nav.
 *
 * @package JTreeHealth
 */
defined('ABSPATH') || exit;

$home  = esc_url(home_url('/'));
$brand = JTREE_THEME_URI . '/assets/brand';
?>
<a class="jth-skip" href="#main">Skip to content</a>

<?php get_template_part('templates/partials/crisis-bar'); ?>

<header class="site-header">
  <div class="site-header-row">
    <a class="brand" href="<?php echo $home; ?>" aria-label="JTree Health home">
      <img
        src="<?php echo esc_url($brand . '/logo-horizontal.png'); ?>"
        srcset="<?php echo esc_url($brand . '/logo-horizontal.png'); ?> 1x, <?php echo esc_url($brand . '/logo-horizontal@2x.png'); ?> 2x"
        alt="Joshua Tree Health">
    </a>
    <nav class="nav" aria-label="Primary">
      <a href="<?php echo $home; ?>programs/"<?php echo is_page('programs') ? ' aria-current="page"' : ''; ?>>Programs</a>
      <a href="<?php echo $home; ?>for-teens/"<?php echo is_page('for-teens') ? ' aria-current="page"' : ''; ?>>For Teens</a>
      <a href="<?php echo $home; ?>for-parents/"<?php echo is_page('for-parents') ? ' aria-current="page"' : ''; ?>>For Parents</a>
      <a href="<?php echo $home; ?>what-we-treat/"<?php echo is_page('what-we-treat') ? ' aria-current="page"' : ''; ?>>What We Treat</a>
      <a href="<?php echo $home; ?>about/"<?php echo is_page('about') ? ' aria-current="page"' : ''; ?>>About</a>
      <a href="<?php echo $home; ?>insurance/"<?php echo is_page('insurance') ? ' aria-current="page"' : ''; ?>>Insurance</a>
      <a class="nav-phone" href="tel:9192764005" aria-label="Call (919) 276-4005">(919) 276-4005</a>
      <a class="jth-btn jth-btn-primary jth-btn-sm nav-cta" href="<?php echo $home; ?>admissions/"<?php echo is_page('admissions') ? ' aria-current="page"' : ''; ?>>Start the Conversation</a>
    </nav>
    <button class="nav-toggle" type="button" aria-label="Toggle menu" aria-expanded="false">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
        <line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/>
      </svg>
    </button>
  </div>
</header>
