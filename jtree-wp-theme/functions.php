<?php
/**
 * Joshua Tree Health — GeneratePress child theme functions.
 *
 * @package JTreeHealth
 * @since 2.0.0
 */

defined('ABSPATH') || exit;

define('JTREE_THEME_VERSION', '2.0.2');
define('JTREE_THEME_DIR', get_stylesheet_directory());
define('JTREE_THEME_URI', get_stylesheet_directory_uri());

/**
 * GTM Container ID. Empty value = no analytics injected (good for local dev
 * unless overridden by an mu-plugin). GTM internally fires the GA4 Google Tag
 * (with measurement ID G-8M90ZXZ1NW) and the GA4 Event tag for inquiry_submitted.
 * Privacy guardrails enforced in GA4 admin: Google Signals off, retention 14mo,
 * no Ads personalization. Cookie consent mode wired separately when banner lands.
 */
define('JTREE_GTM_ID', 'GTM-WGCMNLXH');

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

    // What We Treat — editorial conditions grid.
    if (is_page_template('templates/page-what-we-treat.php')) {
        wp_enqueue_style(
            'jtree-what-we-treat',
            JTREE_THEME_URI . '/assets/css/what-we-treat.css',
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
    $form_handle = null;
    if (is_page(array('admissions', 'contact'))) {
        wp_enqueue_script(
            'jtree-form',
            JTREE_THEME_URI . '/assets/js/form.js',
            array(),
            JTREE_THEME_VERSION,
            true
        );
        $form_handle = 'jtree-form';
    } elseif (is_page('careers')) {
        wp_enqueue_script(
            'jtree-careers',
            JTREE_THEME_URI . '/assets/js/careers.js',
            array(),
            JTREE_THEME_VERSION,
            true
        );
        $form_handle = 'jtree-careers';
    }

    if ($form_handle) {
        // Expose API + thank-you URLs (and Turnstile site key, when set).
        // Turnstile site keys are public — only the secret is sensitive
        // (lives in Vercel env).
        $turnstile_site_key = defined('JTREE_TURNSTILE_SITE_KEY')
            ? JTREE_TURNSTILE_SITE_KEY
            : (get_option('jtree_turnstile_site_key', '') ?: '');
        wp_add_inline_script($form_handle,
            'window.JTREE_CONFIG = ' . wp_json_encode(array(
                'apiUrl'           => apply_filters('jtree_api_url', 'https://api.jtreehealth.com/api/inquiry'),
                'thankYouUrl'      => apply_filters('jtree_thank_you_url', home_url('/thank-you/')),
                'turnstileSiteKey' => apply_filters('jtree_turnstile_site_key', $turnstile_site_key),
            )) . ';',
            'before'
        );

        // Load Turnstile only when a site key is configured. Otherwise the
        // widget never renders and the API verifier falls open — same UX
        // as today, no captcha.
        if (!empty($turnstile_site_key)) {
            wp_enqueue_script(
                'cf-turnstile',
                'https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit',
                array(),
                null,
                true
            );
        }
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
    $templates['templates/page-careers.php']       = 'Careers';
    $templates['templates/page-parent-guide.php']  = 'Parent Guide';
    $templates['templates/page-service-area.php']  = 'Service Area';
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
 * Google Tag Manager — head snippet (loads gtm.js on every page).
 * Skipped if JTREE_GTM_ID is empty — keeps local dev pages out of GTM.
 */
function jtree_gtm_head() {
    if (!defined('JTREE_GTM_ID') || !JTREE_GTM_ID) return;
    $id = esc_js(JTREE_GTM_ID);
    echo "<!-- Google Tag Manager -->\n";
    echo "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{$id}');</script>\n";
    echo "<!-- End Google Tag Manager -->\n";
}
add_action('wp_head', 'jtree_gtm_head', 1);

/**
 * GTM noscript fallback — fires right after <body> opens via wp_body_open hook.
 * Lets users with JS disabled still register a pageview.
 */
function jtree_gtm_noscript() {
    if (!defined('JTREE_GTM_ID') || !JTREE_GTM_ID) return;
    $id = esc_attr(JTREE_GTM_ID);
    echo "<!-- Google Tag Manager (noscript) -->\n";
    echo "<noscript><iframe src=\"https://www.googletagmanager.com/ns.html?id={$id}\" height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript>\n";
    echo "<!-- End Google Tag Manager (noscript) -->\n";
}
add_action('wp_body_open', 'jtree_gtm_noscript');

/**
 * GA4 conversion: push `inquiry_submitted` to the dataLayer on /thank-you/.
 * GTM's `CE - inquiry_submitted` trigger listens for this and fires the GA4 Event tag.
 */
function jtree_thank_you_event() {
    if (!is_page_template('templates/page-thank-you.php') && !is_page('thank-you')) return;
    if (!defined('JTREE_GTM_ID') || !JTREE_GTM_ID) return;
    echo "<script>window.dataLayer=window.dataLayer||[];window.dataLayer.push({event:'inquiry_submitted',event_category:'form',event_label:'inquiry_form'});</script>\n";
}
add_action('wp_head', 'jtree_thank_you_event', 2);

/**
 * Header / footer partials.
 */
function jtree_custom_header() { get_template_part('templates/partials/header-nav'); }
add_action('generate_before_header', 'jtree_custom_header', 5);

function jtree_custom_footer() { get_template_part('templates/partials/site-footer'); }
add_action('generate_after_footer', 'jtree_custom_footer');

function jtree_mobile_sticky_cta() { get_template_part('templates/partials/mobile-sticky-cta'); }
add_action('wp_footer', 'jtree_mobile_sticky_cta', 50);
