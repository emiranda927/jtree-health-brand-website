<?php
/**
 * JTree Health — GeneratePress child theme functions.
 *
 * @package JTreeHealth
 * @since 2.0.0
 */

defined('ABSPATH') || exit;

define('JTREE_THEME_VERSION', '2.0.0');
define('JTREE_THEME_DIR', get_stylesheet_directory());
define('JTREE_THEME_URI', get_stylesheet_directory_uri());

require_once JTREE_THEME_DIR . '/inc/security.php';
require_once JTREE_THEME_DIR . '/inc/forms.php';
require_once JTREE_THEME_DIR . '/inc/seo.php';

/**
 * Enqueue parent + design-system styles. Fonts are bundled locally
 * (./assets/fonts/) and registered via @font-face inside colors_and_type.css —
 * no Google Fonts request, no third-party origin.
 */
function jtree_enqueue_styles() {
    wp_enqueue_style(
        'generatepress-parent',
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme('generatepress')->get('Version')
    );
    wp_enqueue_style(
        'jtree-child',
        get_stylesheet_uri(),
        array('generatepress-parent'),
        JTREE_THEME_VERSION
    );

    // Design system: tokens + @font-face declarations.
    wp_enqueue_style(
        'jtree-tokens',
        JTREE_THEME_URI . '/assets/css/colors_and_type.css',
        array('jtree-child'),
        JTREE_THEME_VERSION
    );

    // Site-wide layout / components / page sections.
    wp_enqueue_style(
        'jtree-site',
        JTREE_THEME_URI . '/assets/css/site.css',
        array('jtree-tokens'),
        JTREE_THEME_VERSION
    );

    // GeneratePress resets — keeps the design system isolated from parent.
    wp_enqueue_style(
        'jtree-wp-glue',
        JTREE_THEME_URI . '/assets/css/wp-glue.css',
        array('jtree-site'),
        JTREE_THEME_VERSION
    );

    // Home page only — heavier composition.
    if (is_front_page() || is_page_template('templates/page-home.php')) {
        wp_enqueue_style(
            'jtree-home',
            JTREE_THEME_URI . '/assets/css/home.css',
            array('jtree-site'),
            JTREE_THEME_VERSION
        );
    }
}
add_action('wp_enqueue_scripts', 'jtree_enqueue_styles');

/**
 * Enqueue scripts.
 */
function jtree_enqueue_scripts() {
    wp_enqueue_script(
        'jtree-nav',
        JTREE_THEME_URI . '/assets/js/nav.js',
        array(),
        JTREE_THEME_VERSION,
        true
    );
    if (is_page(array('admissions', 'contact'))) {
        wp_enqueue_script(
            'jtree-form',
            JTREE_THEME_URI . '/assets/js/form.js',
            array(),
            JTREE_THEME_VERSION,
            true
        );
        // Expose API + thank-you URLs to form.js so they're configurable per env.
        wp_add_inline_script('jtree-form',
            'window.JTREE_CONFIG = ' . wp_json_encode(array(
                'apiUrl'      => apply_filters('jtree_api_url', 'https://api.jtreehealth.com/api/inquiry'),
                'thankYouUrl' => apply_filters('jtree_thank_you_url', home_url('/thank-you/')),
            )) . ';',
            'before'
        );
    }
}
add_action('wp_enqueue_scripts', 'jtree_enqueue_scripts');

/**
 * Suppress GeneratePress's built-in header so our custom nav partial
 * (injected via generate_before_header) is the only header rendered.
 */
add_action('init', function() {
    remove_action('generate_header', 'generate_construct_header');
});

/**
 * On custom page templates, suppress GP's default content container
 * so templates have full control over page layout.
 */
add_filter('generate_do_default_template_action', function($do) {
    if (is_page_template()) return false;
    return $do;
});

/**
 * Disable GeneratePress's sidebar reservation on every static page. Without
 * this, GP reserves ~264px on the right of the outer .site wrapper for a
 * sidebar that doesn't render — pushing content off-center. Returning
 * 'no-sidebar' tells GP not to reserve that space.
 *
 * Applied to all pages (including ones that may have lost their template
 * assignment) and the front page. Posts/archives are unaffected.
 */
add_filter('generate_sidebar_layout', function($layout) {
    if (is_page() || is_front_page() || is_home()) return 'no-sidebar';
    return $layout;
});

/**
 * Register page templates.
 */
function jtree_page_templates($templates) {
    $templates['templates/page-home.php']          = 'Home';
    $templates['templates/page-programs.php']      = 'Programs';
    $templates['templates/page-what-we-treat.php'] = 'What We Treat';
    $templates['templates/page-for-parents.php']   = 'For Parents';
    $templates['templates/page-for-teens.php']     = 'For Teens';
    $templates['templates/page-insurance.php']     = 'Insurance';
    $templates['templates/page-admissions.php']    = 'Admissions';
    $templates['templates/page-about.php']         = 'About';
    $templates['templates/page-contact.php']       = 'Contact';
    $templates['templates/page-thank-you.php']     = 'Thank You';
    $templates['templates/page-privacy.php']       = 'Privacy Policy';
    $templates['templates/page-crisis.php']        = 'Crisis Resources';
    return $templates;
}
add_filter('theme_page_templates', 'jtree_page_templates');

/**
 * Theme support.
 */
function jtree_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form','comment-form','comment-list','gallery','caption','style','script'));
}
add_action('after_setup_theme', 'jtree_theme_setup');

/**
 * Body classes: `.jth-paper` site-wide; `.home-v2` on the home template
 * (scopes home.css's editorial layout).
 */
function jtree_body_class($classes) {
    $classes[] = 'jth-paper';
    if (is_front_page() || is_page_template('templates/page-home.php')) {
        $classes[] = 'home-v2';
    }
    return $classes;
}
add_filter('body_class', 'jtree_body_class');

/**
 * GA4 conversion: push `inquiry_submitted` only on the thank-you page,
 * before any GTM config so it lands in the first dataLayer batch.
 */
function jtree_thank_you_dataLayer() {
    if (!is_page_template('templates/page-thank-you.php') && !is_page('thank-you')) return;
    echo "<script>window.dataLayer=window.dataLayer||[];window.dataLayer.push({event:'inquiry_submitted'});</script>\n";
}
add_action('wp_head', 'jtree_thank_you_dataLayer', 1);

/**
 * Header / footer partials.
 */
function jtree_custom_header() { get_template_part('templates/partials/header-nav'); }
add_action('generate_before_header', 'jtree_custom_header', 5);

function jtree_custom_footer() { get_template_part('templates/partials/site-footer'); }
add_action('generate_after_footer', 'jtree_custom_footer');
