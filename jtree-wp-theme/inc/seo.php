<?php
/**
 * JTree Health - SEO: Schema.org & OpenGraph
 *
 * @package JTreeHealth
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Output Schema.org MedicalBusiness JSON-LD
 */
function jtree_schema_jsonld() {
    $schema = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'MedicalBusiness',
        'name'        => 'JTree Health',
        'description' => 'Adolescent PHP (Partial Hospitalization) and IOP (Intensive Outpatient) mental health programs for teens ages 10-17 in Apex, NC.',
        'url'         => home_url('/'),
        'telephone'   => '(919) 276-4005',
        'address'     => array(
            '@type'           => 'PostalAddress',
            'addressLocality' => 'Apex',
            'addressRegion'   => 'NC',
            'postalCode'      => '27502',
            'addressCountry'  => 'US',
        ),
        'geo'         => array(
            '@type'     => 'GeoCoordinates',
            'latitude'  => '35.7327',
            'longitude' => '-78.8503',
        ),
        'medicalSpecialty' => 'Psychiatric',
        'availableService' => array(
            array(
                '@type'       => 'MedicalTherapy',
                'name'        => 'Partial Hospitalization Program (PHP)',
                'description' => '5 days a week, 9am-3pm intensive structured mental health support for teens.',
            ),
            array(
                '@type'       => 'MedicalTherapy',
                'name'        => 'Intensive Outpatient Program (IOP)',
                'description' => '3 days a week, 3-6pm after-school intensive outpatient therapy for teens.',
            ),
            array(
                '@type'       => 'MedicalTherapy',
                'name'        => 'Medication Management',
                'description' => 'Psychiatric evaluation and medication monitoring alongside PHP or IOP.',
            ),
        ),
        'areaServed'  => array(
            '@type' => 'GeoCircle',
            'geoMidpoint' => array(
                '@type'     => 'GeoCoordinates',
                'latitude'  => '35.7327',
                'longitude' => '-78.8503',
            ),
            'geoRadius' => '50000',
        ),
        // priceRange omitted: meaningless for adolescent mental healthcare; use insurance pages instead.
        // openingHoursSpecification per program; PHP runs Mon–Fri 9–3, IOP Tue/Wed/Thu 3–6.
        'openingHoursSpecification' => array(
            array(
                '@type'   => 'OpeningHoursSpecification',
                'dayOfWeek' => array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'),
                'opens'   => '09:00',
                'closes'  => '15:00',
                'name'    => 'PHP',
            ),
            array(
                '@type'   => 'OpeningHoursSpecification',
                'dayOfWeek' => array('Tuesday', 'Wednesday', 'Thursday'),
                'opens'   => '15:00',
                'closes'  => '18:00',
                'name'    => 'IOP',
            ),
        ),
        'sameAs'      => array(
            'https://www.facebook.com/jtreehealth',
            'https://www.instagram.com/jtreehealth',
            'https://www.linkedin.com/company/jtreehealth',
        ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
}
add_action('wp_head', 'jtree_schema_jsonld', 1);

/**
 * Output OpenGraph meta tags
 */
function jtree_opengraph_meta() {
    $title       = wp_get_document_title();
    $description = 'JTree Health provides PHP and IOP mental health programs for teens ages 10-17 in Apex, NC. CARF Accredited. Call (919) 276-4005.';
    $url         = home_url(add_query_arg(array(), wp_unslash($_SERVER['REQUEST_URI'])));
    $site_name   = 'JTree Health';
    // og-image placeholder — replace before launch with a 1200×630 .jpg.
    $image       = JTREE_THEME_URI . '/assets/brand/og-image.jpg';

    // Page-specific descriptions
    if (is_page('programs')) {
        $description = 'PHP and IOP mental health programs for teens ages 10-17 at JTree Health in Apex, NC.';
    } elseif (is_page('about')) {
        $description = 'Learn about JTree Health — adolescent PHP and IOP in Apex, NC. Founded 2026.';
    } elseif (is_page('admissions')) {
        $description = 'Start the admissions process at JTree Health. Free intake assessment. Most families start within 3-5 business days.';
    } elseif (is_page('contact')) {
        $description = 'Contact JTree Health at (919) 276-4005. Adolescent mental health IOP and PHP in Apex, NC.';
    } elseif (is_page('insurance')) {
        $description = 'JTree Health accepts BCBS, Cigna, Aetna, UHC, and Tricare. We handle insurance verification for you.';
    } elseif (is_page('what-we-treat')) {
        $description = 'JTree Health treats anxiety, depression, OCD, ADHD, trauma, PTSD, self-harm, and co-occurring disorders in teens.';
    } elseif (is_page('for-parents')) {
        $description = 'Information for parents considering PHP or IOP for their teen. JTree Health in Apex, NC.';
    } elseif (is_page('for-teens')) {
        $description = 'You\'re not broken. You\'re overwhelmed. Learn what JTree Health is actually like for teens.';
    } elseif (is_page('crisis')) {
        $description = 'Crisis resources: 988 Suicide & Crisis Lifeline, Crisis Text Line. If your teen is in danger, call 911.';
    }

    echo '<meta property="og:type" content="website" />' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($description) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url($url) . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '" />' . "\n";
    echo '<meta property="og:image" content="' . esc_url($image) . '" />' . "\n";
    echo '<meta property="og:locale" content="en_US" />' . "\n";

    // Twitter card
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($description) . '" />' . "\n";
    echo '<meta name="twitter:image" content="' . esc_url($image) . '" />' . "\n";

    // Standard meta description
    echo '<meta name="description" content="' . esc_attr($description) . '" />' . "\n";
}
add_action('wp_head', 'jtree_opengraph_meta', 2);

/**
 * Output favicon set + theme-color (deep green).
 */
function jtree_favicon_links() {
    $base = JTREE_THEME_URI . '/assets/brand';
    echo '<link rel="icon" type="image/png" sizes="32x32"  href="' . esc_url($base . '/favicon-32.png')  . '" />' . "\n";
    echo '<link rel="icon" type="image/png" sizes="64x64"  href="' . esc_url($base . '/favicon-64.png')  . '" />' . "\n";
    echo '<link rel="icon" type="image/png" sizes="128x128" href="' . esc_url($base . '/favicon-128.png') . '" />' . "\n";
    echo '<link rel="apple-touch-icon" sizes="180x180" href="' . esc_url($base . '/app-icon-180.png') . '" />' . "\n";
    echo '<meta name="theme-color" content="#183B2E" />' . "\n";
}
add_action('wp_head', 'jtree_favicon_links', 1);

/**
 * `noindex` the thank-you page (post-conversion, never crawl).
 */
function jtree_robots_thank_you() {
    if (is_page('thank-you') || is_page_template('templates/page-thank-you.php')) {
        echo '<meta name="robots" content="noindex, nofollow" />' . "\n";
    }
}
add_action('wp_head', 'jtree_robots_thank_you', 1);

/**
 * Set canonical URL + locale on every page.
 */
function jtree_canonical_link() {
    $url = home_url(add_query_arg(array(), wp_unslash($_SERVER['REQUEST_URI'])));
    echo '<link rel="canonical" href="' . esc_url($url) . '" />' . "\n";
    // Note: <meta http-equiv="content-language"> is deprecated. Rely on <html lang="en">.
}
add_action('wp_head', 'jtree_canonical_link', 2);

/**
 * Exclude /thank-you/ from the WordPress core sitemap (5.5+).
 */
function jtree_sitemap_exclude_thanks($args, $object_subtype) {
    if ($object_subtype === 'page') {
        $thank_you = get_page_by_path('thank-you');
        if ($thank_you) {
            $excluded = isset($args['post__not_in']) ? (array) $args['post__not_in'] : array();
            $excluded[] = $thank_you->ID;
            $args['post__not_in'] = $excluded;
        }
    }
    return $args;
}
add_filter('wp_sitemaps_posts_query_args', 'jtree_sitemap_exclude_thanks', 10, 2);

/**
 * Augment the dynamic robots.txt that WordPress serves at /robots.txt.
 */
function jtree_robots_txt($output, $public) {
    if (!$public) return $output; // staging / private
    $extra  = "\n";
    $extra .= "User-agent: *\n";
    $extra .= "Disallow: /thank-you/\n";
    $extra .= "Disallow: /wp-admin/\n";
    $extra .= "Allow: /wp-admin/admin-ajax.php\n\n";
    $extra .= "Sitemap: " . home_url('/wp-sitemap.xml') . "\n";
    return $output . $extra;
}
add_filter('robots_txt', 'jtree_robots_txt', 10, 2);
