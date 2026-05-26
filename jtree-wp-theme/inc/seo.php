<?php
/**
 * Joshua Tree Health - SEO: Schema.org & OpenGraph
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
        'name'        => 'Joshua Tree Health',
        'description' => 'Adolescent PHP (Partial Hospitalization) and IOP (Intensive Outpatient) mental health programs for teens ages 10-17 in Apex, NC.',
        'url'         => home_url('/'),
        'telephone'   => '(919) 335-5053',
        'address'     => array(
            '@type'           => 'PostalAddress',
            'streetAddress'   => '800 West Williams St., STE 203',
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
                'name'        => 'Intensive Outpatient Program (IOP)',
                'description' => 'Intensive outpatient therapy for adolescents ages 10-17, delivered in-person and virtually across multiple time blocks per week. 12-week DBT and polyvagal program.',
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
        // openingHoursSpecification: IOP group blocks. In-person afternoons Mon/Tue/Thu 4-7 PM;
        // virtual mornings Tue/Thu/Sat 9 AM-12 PM. PHP omitted until launch.
        'openingHoursSpecification' => array(
            array(
                '@type'     => 'OpeningHoursSpecification',
                'dayOfWeek' => array('Monday', 'Tuesday', 'Thursday'),
                'opens'     => '16:00',
                'closes'    => '19:00',
                'name'      => 'IOP (in-person)',
            ),
            array(
                '@type'     => 'OpeningHoursSpecification',
                'dayOfWeek' => array('Tuesday', 'Thursday', 'Saturday'),
                'opens'     => '09:00',
                'closes'    => '12:00',
                'name'      => 'IOP (virtual)',
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
 * Page-specific FAQ content. Each entry is a real Q&A grounded in the page's
 * copy — answers must match what the page actually says or Google may flag
 * the rich result. Update both this map and the page when copy changes.
 *
 * @return array<string, array<int, array{q:string,a:string}>>
 */
function jtree_faq_map() {
    return array(
        'insurance' => array(
            array(
                'q' => 'Which insurance plans is Joshua Tree Health in network with?',
                'a' => 'We are in network with BlueCross BlueShield (BCBS NC), Cigna / Evernorth, Aetna, and Tricare.',
            ),
            array(
                'q' => "What if my plan isn't listed?",
                'a' => 'Call us. We sometimes accept out-of-network on a case-by-case basis, and we can help you understand your single-case-agreement options.',
            ),
            array(
                'q' => 'How does insurance verification work?',
                'a' => 'After you submit the inquiry form, our admissions team contacts your insurer and confirms PHP or IOP benefits, deductible, and any prior-authorization requirement before your teen starts.',
            ),
            array(
                'q' => 'Will I know what I owe before treatment starts?',
                'a' => 'Yes. We give you a clear estimate of your out-of-pocket cost before your teen\'s first day, so the cost question is answered before treatment begins.',
            ),
        ),
        'programs' => array(
            array(
                'q' => 'Which programs does Joshua Tree Health offer?',
                'a' => 'Our Intensive Outpatient Program (IOP) is open today, with in-person afternoon groups in Apex (Mon, Tue, Thu, 4-7 PM) and virtual morning groups (Tue, Thu, Sat, 9 AM-12 PM). Each teen attends three group blocks per week. A Partial Hospitalization Program (PHP) is launching soon, running Monday through Friday from 9 a.m. to 3 p.m. Both are outpatient — your teen sleeps at home.',
            ),
            array(
                'q' => "What's the difference between PHP and IOP?",
                'a' => 'IOP (Intensive Outpatient) is after-school care for teens who can stay in school but need more than a weekly therapist. PHP (Partial Hospitalization, launching soon at Joshua Tree Health) is a five-day-a-week daytime program for teens whose mental-health needs make a typical school day untenable right now.',
            ),
            array(
                'q' => 'What ages does Joshua Tree Health treat?',
                'a' => 'Adolescents ages 10 through 17.',
            ),
            array(
                'q' => 'Is Joshua Tree Health an inpatient program?',
                'a' => 'No. Both IOP and the upcoming PHP are outpatient. There is no inpatient stay and no 30-day mandate; your teen sleeps in their own bed.',
            ),
            array(
                'q' => 'What happens during a day in PHP?',
                'a' => "When PHP opens, a typical day will include two DBT skills groups, one process group with peers, individual therapy, family sessions every other week, nervous-system regulation work, and academic support so your teen keeps up with school.",
            ),
            array(
                'q' => 'What does a typical IOP group block look like?',
                'a' => 'Every IOP session follows the same predictable arc, so your teen always knows what is coming next: check-in and diary cards, opening mindfulness, psychoeducation on the day\'s skill, interactive skill practice, processing and integration, a group activity, and check-out and diary cards. Each block runs 3 hours, either in-person in Apex or virtually.',
            ),
            array(
                'q' => 'How long is the IOP program?',
                'a' => 'Twelve weeks. The arc moves from nervous-system foundations and mindfulness, into distress tolerance, emotion regulation, interpersonal effectiveness, and finally walking the middle path — which includes a dedicated week on parent-teen escalation patterns and repair. Teens close out with a personal regulation plan and a letter to their future self.',
            ),
            array(
                'q' => 'How are parents involved in IOP?',
                'a' => 'Parents are part of the work, not bystanders. Your teen has a family session every two weeks, and the last module of the program (Walking the Middle Path) is dedicated to parent-teen escalation patterns, repair after conflict, and co-regulation. We teach families that most fights are about the pattern, not the topic.',
            ),
            array(
                'q' => 'What happens after my teen finishes IOP?',
                'a' => "Toward the end of the 12 weeks, every teen builds a personal regulation plan: their early warning signs, the skills that work for them, and who they reach out to when things get hard. We talk about this as relapse prevention — not because struggle disappears, but because they leave with a plan for when it returns.",
            ),
        ),
        'what-we-treat' => array(
            array(
                'q' => 'What conditions does Joshua Tree Health treat?',
                'a' => 'We treat anxiety and panic, depression, OCD, ADHD with emotion dysregulation, trauma and PTSD, self-harm, Autism Level 1, school avoidance, and co-occurring concerns in adolescents ages 10 to 17.',
            ),
            array(
                'q' => 'Do you treat substance use or eating disorders?',
                'a' => 'We do not treat substance use as a primary diagnosis or active eating disorders that require medical stabilization. If that\'s what your teen needs, we will point you to the right place.',
            ),
            array(
                'q' => 'My teen has more than one of these concerns at once. Can you still help?',
                'a' => 'Yes. Most teens we see are managing more than one concern at the same time. We work with the whole picture rather than treating a single diagnosis in isolation.',
            ),
            array(
                'q' => 'What if Joshua Tree Health is not the right fit for my teen?',
                'a' => 'Tell us a little about what is going on. The admissions team will help you figure out the next step, even if that step is a different program.',
            ),
            array(
                'q' => 'What is DBT?',
                'a' => 'DBT stands for Dialectical Behavior Therapy. It teaches four sets of practical skills: mindfulness (noticing what is happening without reacting), distress tolerance (getting through hard moments without making them worse), emotion regulation (catching feelings earlier and working with them), and interpersonal effectiveness (asking for what you need and handling conflict). It was originally developed for adults with intense emotional reactivity and has strong evidence for adolescents.',
            ),
            array(
                'q' => 'What is polyvagal theory, in plain language?',
                'a' => 'Polyvagal theory is a way of understanding how the nervous system reacts to stress. It describes three states: feeling safe and connected, feeling anxious or activated (fight or flight), and feeling shut down or numb. We teach teens to notice which state they are in and use specific skills to help their body return to safety. It is not a replacement for DBT; we use them together.',
            ),
            array(
                'q' => 'What is the Safe and Sound Protocol (SSP)?',
                'a' => 'SSP is a listening-based intervention that supports nervous-system regulation. A teen listens to specially-filtered music in short sessions, typically over a few weeks. It is one of several tools we use alongside DBT when clinically indicated; it is not a standalone treatment.',
            ),
        ),
    );
}

/**
 * Output FAQPage JSON-LD on pages that carry FAQ content. Rich-result
 * eligible — answers must remain in sync with the visible page copy.
 */
function jtree_faq_schema_jsonld() {
    $map = jtree_faq_map();
    $slug = null;
    foreach (array_keys($map) as $candidate) {
        if (is_page($candidate)) { $slug = $candidate; break; }
    }
    if (!$slug) return;

    $entities = array();
    foreach ($map[$slug] as $qa) {
        $entities[] = array(
            '@type'          => 'Question',
            'name'           => $qa['q'],
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => $qa['a'],
            ),
        );
    }

    $schema = array(
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => $entities,
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
}
add_action('wp_head', 'jtree_faq_schema_jsonld', 3);

/**
 * Output OpenGraph meta tags
 */
function jtree_opengraph_meta() {
    $title       = wp_get_document_title();
    $description = 'Joshua Tree Health provides PHP and IOP mental health programs for teens ages 10-17 in Apex, NC. CARF Accredited. Call (919) 335-5053.';
    $url         = home_url(add_query_arg(array(), wp_unslash($_SERVER['REQUEST_URI'])));
    $site_name   = 'Joshua Tree Health';
    // og-image placeholder — replace before launch with a 1200×630 .jpg.
    $image       = JTREE_THEME_URI . '/assets/brand/og-image.jpg';

    // Page-specific descriptions
    if (is_page('programs')) {
        $description = 'PHP and IOP mental health programs for teens ages 10-17 at Joshua Tree Health in Apex, NC.';
    } elseif (is_page('about')) {
        $description = 'Learn about Joshua Tree Health — adolescent PHP and IOP in Apex, NC. Founded 2026.';
    } elseif (is_page('admissions')) {
        $description = 'Start the conversation with Joshua Tree Health. Fill out the form and we\'ll call you within 2-4 business hours. Adolescent PHP and IOP in Apex, NC.';
    } elseif (is_page('contact')) {
        $description = 'Contact Joshua Tree Health at (919) 335-5053. Adolescent mental health IOP and PHP in Apex, NC.';
    } elseif (is_page('insurance')) {
        $description = 'Joshua Tree Health accepts BCBS, Cigna, Aetna, and Tricare. We handle insurance verification for you.';
    } elseif (is_page('what-we-treat')) {
        $description = 'Joshua Tree Health treats anxiety, depression, OCD, ADHD, trauma, PTSD, self-harm, and co-occurring disorders in teens.';
    } elseif (is_page('for-parents')) {
        $description = 'Information for parents considering PHP or IOP for their teen. Joshua Tree Health in Apex, NC.';
    } elseif (is_page('for-teens')) {
        $description = 'You\'re not broken. You\'re overwhelmed. Learn what Joshua Tree Health is actually like for teens.';
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
