<?php
/**
 * JTree Health - GeneratePress Child Theme Functions
 *
 * @package JTreeHealth
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Define theme constants
 */
define('JTREE_THEME_VERSION', '1.0.0');
define('JTREE_THEME_DIR', get_stylesheet_directory());
define('JTREE_THEME_URI', get_stylesheet_directory_uri());

/**
 * Include required files
 */
require_once JTREE_THEME_DIR . '/inc/security.php';
require_once JTREE_THEME_DIR . '/inc/forms.php';
require_once JTREE_THEME_DIR . '/inc/seo.php';

/**
 * Enqueue parent and child theme styles
 */
function jtree_enqueue_styles() {
    // Parent theme style
    wp_enqueue_style(
        'generatepress-parent',
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme('generatepress')->get('Version')
    );

    // Child theme style
    wp_enqueue_style(
        'jtree-child',
        get_stylesheet_uri(),
        array('generatepress-parent'),
        JTREE_THEME_VERSION
    );

    // Main custom styles
    wp_enqueue_style(
        'jtree-main',
        JTREE_THEME_URI . '/assets/css/main.css',
        array('jtree-child'),
        JTREE_THEME_VERSION
    );

    // Google Fonts: Nunito + Plus Jakarta Sans (full weights to match design system)
    wp_enqueue_style(
        'jtree-google-fonts',
        'https://fonts.googleapis.com/css2?family=Nunito:wght@700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap',
        array(),
        null
    );
}
add_action('wp_enqueue_scripts', 'jtree_enqueue_styles');

/**
 * Enqueue scripts
 */
function jtree_enqueue_scripts() {
    // Scroll animations — site-wide
    wp_enqueue_script(
        'jtree-animations',
        JTREE_THEME_URI . '/assets/js/animations.js',
        array(),
        JTREE_THEME_VERSION,
        true
    );

    // Form handler — admissions and contact pages only
    if (is_page(array('admissions', 'contact'))) {
        wp_enqueue_script(
            'jtree-form',
            JTREE_THEME_URI . '/assets/js/form.js',
            array(),
            JTREE_THEME_VERSION,
            true
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
 * Register navigation menus
 */
function jtree_register_menus() {
    register_nav_menus(array(
        'jtree-primary'  => __('Primary Navigation', 'jtree-health'),
        'jtree-footer'   => __('Footer Navigation', 'jtree-health'),
    ));
}
add_action('after_setup_theme', 'jtree_register_menus');

/**
 * Register page templates
 */
function jtree_page_templates($templates) {
    $templates['templates/page-home.php']          = 'Home';
    $templates['templates/page-programs.php']       = 'Programs';
    $templates['templates/page-what-we-treat.php']  = 'What We Treat';
    $templates['templates/page-for-parents.php']    = 'For Parents';
    $templates['templates/page-for-teens.php']      = 'For Teens';
    $templates['templates/page-insurance.php']      = 'Insurance';
    $templates['templates/page-admissions.php']     = 'Admissions';
    $templates['templates/page-about.php']          = 'About';
    $templates['templates/page-contact.php']        = 'Contact';
    $templates['templates/page-thank-you.php']      = 'Thank You';
    $templates['templates/page-privacy.php']        = 'Privacy Policy';
    $templates['templates/page-crisis.php']         = 'Crisis Resources';
    return $templates;
}
add_filter('theme_page_templates', 'jtree_page_templates');

/**
 * Add theme support
 */
function jtree_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
}
add_action('after_setup_theme', 'jtree_theme_setup');

/**
 * Add preconnect for Google Fonts
 */
function jtree_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        $urls[] = array(
            'href' => 'https://fonts.googleapis.com',
            'crossorigin' => true,
        );
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin' => true,
        );
    }
    return $urls;
}
add_filter('wp_resource_hints', 'jtree_resource_hints', 10, 2);

/**
 * Load custom header navigation partial
 */
function jtree_custom_header() {
    get_template_part('templates/partials/header-nav');
}
add_action('generate_before_header', 'jtree_custom_header', 5);

/**
 * Load custom footer partial
 */
function jtree_custom_footer() {
    get_template_part('templates/partials/site-footer');
}
add_action('generate_after_footer', 'jtree_custom_footer');
