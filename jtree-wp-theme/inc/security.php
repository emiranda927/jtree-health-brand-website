<?php
/**
 * JTree Health - Security Hardening
 *
 * @package JTreeHealth
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Disable file editor in wp-admin
 */
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}

/**
 * Disable XML-RPC entirely
 */
add_filter('xmlrpc_enabled', '__return_false');

/**
 * Remove XML-RPC methods
 */
function jtree_remove_xmlrpc_methods($methods) {
    return array();
}
add_filter('xmlrpc_methods', 'jtree_remove_xmlrpc_methods');

/**
 * Remove XML-RPC discovery link from head
 */
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');

/**
 * Remove WordPress version from headers and RSS
 */
function jtree_remove_wp_version() {
    return '';
}
add_filter('the_generator', 'jtree_remove_wp_version');

/**
 * Remove version from scripts and styles
 */
function jtree_remove_version_strings($src) {
    if (strpos($src, 'ver=') && strpos($src, home_url()) !== false) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'jtree_remove_version_strings', 9999);
add_filter('script_loader_src', 'jtree_remove_version_strings', 9999);

/**
 * Remove WP version meta tag
 */
remove_action('wp_head', 'wp_generator');

/**
 * Send security headers
 */
function jtree_security_headers() {
    if (headers_sent()) {
        return;
    }

    // Force HTTPS with HSTS (1 year, includeSubDomains)
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');

    // Prevent clickjacking
    header('X-Frame-Options: DENY');

    // Prevent MIME-type sniffing
    header('X-Content-Type-Options: nosniff');

    // XSS Protection
    header('X-XSS-Protection: 1; mode=block');

    // Referrer policy
    header('Referrer-Policy: strict-origin-when-cross-origin');

    // Permissions policy
    header("Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()");

    // Content Security Policy
    $csp = implode('; ', array(
        "default-src 'self'",
        "script-src 'self' 'unsafe-inline' https://www.googletagmanager.com https://www.google-analytics.com https://www.googleadservices.com https://googleads.g.doubleclick.net",
        "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
        "font-src 'self' https://fonts.gstatic.com",
        "img-src 'self' data: https://www.google-analytics.com https://www.googletagmanager.com https://*.cloudflare.com",
        "connect-src 'self' https://api.jtreehealth.com https://www.google-analytics.com https://www.googletagmanager.com https://analytics.google.com https://*.cloudflare.com",
        "frame-src 'none'",
        "object-src 'none'",
        "base-uri 'self'",
        "form-action 'self' https://api.jtreehealth.com",
    ));
    header("Content-Security-Policy: {$csp}");
}
add_action('send_headers', 'jtree_security_headers');

/**
 * Force HTTPS redirect
 */
function jtree_force_https() {
    if (!is_ssl() && !is_admin()) {
        $redirect_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        wp_redirect($redirect_url, 301);
        exit;
    }
}
add_action('template_redirect', 'jtree_force_https');

/**
 * Enable auto-updates for minor core releases
 */
add_filter('allow_minor_auto_core_updates', '__return_true');

/**
 * Enable auto-updates for plugins
 */
add_filter('auto_update_plugin', '__return_true');

/**
 * Enable auto-updates for themes
 */
add_filter('auto_update_theme', '__return_true');

/**
 * Limit login attempts (basic implementation)
 */
function jtree_limit_login_attempts() {
    $ip = $_SERVER['REMOTE_ADDR'];
    $transient_key = 'jtree_login_attempts_' . md5($ip);
    $attempts = get_transient($transient_key);

    if ($attempts !== false && $attempts >= 5) {
        wp_die(
            __('Too many failed login attempts. Please try again in 15 minutes.', 'jtree-health'),
            __('Login Locked', 'jtree-health'),
            array('response' => 429)
        );
    }
}
add_action('wp_login_failed', function () {
    $ip = $_SERVER['REMOTE_ADDR'];
    $transient_key = 'jtree_login_attempts_' . md5($ip);
    $attempts = get_transient($transient_key);

    if ($attempts === false) {
        set_transient($transient_key, 1, 15 * MINUTE_IN_SECONDS);
    } else {
        set_transient($transient_key, $attempts + 1, 15 * MINUTE_IN_SECONDS);
    }
});
add_action('wp_login', function () {
    $ip = $_SERVER['REMOTE_ADDR'];
    $transient_key = 'jtree_login_attempts_' . md5($ip);
    delete_transient($transient_key);
});
add_action('login_form', 'jtree_limit_login_attempts');

/**
 * Disable REST API user enumeration for non-authenticated users
 */
function jtree_restrict_user_rest_api($response, $handler, $request) {
    $route = $request->get_route();
    if (strpos($route, '/wp/v2/users') !== false && !current_user_can('list_users')) {
        return new WP_Error(
            'rest_forbidden',
            __('You do not have permission to access this resource.', 'jtree-health'),
            array('status' => 403)
        );
    }
    return $response;
}
add_filter('rest_request_before_callbacks', 'jtree_restrict_user_rest_api', 10, 3);

/**
 * Remove unnecessary header links
 */
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'wp_oembed_add_discovery_links');
