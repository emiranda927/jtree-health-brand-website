<?php
/**
 * Template Name: Careers
 *
 * @package JTreeHealth
 */
defined('ABSPATH') || exit;
get_header();

$openings = array(
    array(
        'role'       => 'Therapist (Associate or Fully Licensed)',
        'commitment' => 'Full- or part-time &middot; W-2 &middot; On-site (Apex) + remote',
        'summary'    => 'Carry an 8-client caseload across IOP groups, individual therapy, and family sessions. Lead DBT skills groups for adolescents 10&ndash;17. LCSW, LPC, LMFT, or associate equivalent in NC.',
    ),
    array(
        'role'       => 'Qualified Professional (QP)',
        'commitment' => 'Full- or part-time &middot; W-2 &middot; On-site (Apex) + remote',
        'summary'    => 'Support group facilitation, skill reinforcement, and the continuity between sessions that holds an IOP together.',
    ),
    array(
        'role'       => 'Open application',
        'commitment' => "Don't see your role?",
        'summary'    => "We're growing fast and adding clinical and operational roles ahead of our PHP launch. Tell us about you and what you'd want to build with us.",
    ),
);
?>

<main id="main">

  <section class="section" style="padding-top: 64px; padding-bottom: 24px;">
    <div class="container" style="max-width: 880px;">
      <span class="jth-eyebrow" style="display:inline-block; margin-bottom: 12px;">Careers</span>
      <h1 class="jth-display-l" style="font-size: clamp(40px, 5vw, 56px); margin: 0 0 20px;">Build something <em class="jth-emph">honest</em> with us.</h1>
      <p class="jth-body-l" style="margin: 0 0 16px; max-width: 60ch;">Joshua Tree Health is an adolescent behavioral-health program in Apex, NC, growing through the rebrand from Simply Teens Outpatient and the launch of our PHP this fall. The clinician who joins us now becomes a foundational part of that growth.</p>
      <p class="jth-body-l" style="margin: 0; max-width: 60ch;">We're hiring people who care more about teens than they do about census.</p>
    </div>
  </section>

  <section class="section section-bg-cream-2">
    <div class="container" style="max-width: 880px;">
      <h2 class="jth-h2" style="margin: 0 0 16px;">How we work, clinically.</h2>
      <p class="jth-body-l" style="margin: 0 0 16px;">Our model is grounded in <strong>DBT, polyvagal theory, the Safe and Sound Protocol, STARR Commonwealth trauma programs, and resilience-based therapies</strong>. We're CARF-accredited and state-licensed.</p>
      <p class="jth-body-l" style="margin: 0;">We are LGBTQ+-affirming and neurodivergent-friendly, and every clinical decision is built around what's actually best for the young person in front of us.</p>
    </div>
  </section>

  <section class="section">
    <div class="container" style="max-width: 880px;">
      <h2 class="jth-h2" style="margin: 0 0 24px;">Open roles</h2>
      <div class="jth-careers-list">
        <?php foreach ($openings as $job) : ?>
          <article class="jth-career-card">
            <div class="jth-career-card__head">
              <h3 class="jth-h4" style="margin:0;"><?php echo esc_html($job['role']); ?></h3>
              <p class="jth-meta" style="margin: 6px 0 0;"><?php echo wp_kses_post($job['commitment']); ?></p>
            </div>
            <p style="margin: 12px 0 0;"><?php echo esc_html($job['summary']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section section-bg-cream-2">
    <div class="container" style="max-width: 880px;">
      <h2 class="jth-h2" style="margin: 0 0 20px;">What the role entails (Therapist)</h2>
      <ul style="font-size: 18px; line-height: 1.7; padding-left: 20px; margin: 0 0 32px;">
        <li>Carry a caseload of 8 clients; up to 25 clinical hours per week</li>
        <li>Run IOP groups as part of a multidisciplinary team</li>
        <li>Provide individual and family sessions (Wednesdays and Fridays in our schedule)</li>
        <li>Conduct clinical intake assessments</li>
        <li>Hold one weekly crisis-therapy slot</li>
        <li>Attend 90-minute weekly case consultation + 30-minute weekly admin meeting</li>
        <li>Collaborate with community providers (schools, pediatricians, outpatient referrers)</li>
        <li>Keep authorizations and documentation current daily</li>
        <li>Up to 40 hours per week total, including all direct client hours and admin time</li>
      </ul>

      <h2 class="jth-h2" style="margin: 0 0 20px;">What you bring</h2>
      <ul style="font-size: 18px; line-height: 1.7; padding-left: 20px; margin: 0 0 32px;">
        <li>NC licensure (LCSW, LPC, LMFT) in good standing, or associate license, or QP credential</li>
        <li>Clinical experience in individual and group therapy; assessments; treatment planning</li>
        <li>Familiarity with DBT and trauma-informed care</li>
        <li>Working knowledge of levels of care (IOP, PHP, OP) and HIPAA / mandated reporting</li>
        <li>Cultural competence with diverse adolescents and families</li>
        <li>At least one year of adolescent experience is a plus</li>
      </ul>

      <h2 class="jth-h2" style="margin: 0 0 20px;">What we offer</h2>
      <ul style="font-size: 18px; line-height: 1.7; padding-left: 20px; margin: 0;">
        <li>W-2 employment with health, dental, and vision</li>
        <li>Short-term disability</li>
        <li>Weekly case consultation</li>
        <li>Continuing education credits (CEs)</li>
        <li>CPR &amp; First Aid training</li>
        <li>Company laptop</li>
        <li>Paths into lead-clinician, intern-supervisor, and associate-supervisor roles</li>
      </ul>
    </div>
  </section>

  <section class="section" id="apply">
    <div class="container" style="max-width: 720px;">
      <span class="jth-eyebrow" style="display:inline-block; margin-bottom: 12px;">Apply</span>
      <h2 class="jth-h2" style="margin: 0 0 16px;">Tell us about you.</h2>
      <p class="jth-body-l" style="margin: 0 0 24px; max-width: 60ch; color: var(--jth-fg-muted);">Short and honest beats long and polished. You can fill out the form below, or email your resume and a few lines about what brings you here to <a href="mailto:gabriela@jtreehealth.com">gabriela@jtreehealth.com</a>.</p>

      <?php if (function_exists('jtree_render_careers_form')) jtree_render_careers_form(); ?>

      <div id="careers-thankyou" class="jth-careers-thanks" hidden>
        <h2 class="jth-h2" style="margin: 0 0 12px;">Thanks &mdash; we'll be in touch.</h2>
        <p class="jth-body-l" style="margin: 0; max-width: 56ch;">We read every application personally. You'll hear from us within a week.</p>
      </div>

      <p class="jth-body-s" style="margin: 32px 0 0; color: var(--jth-fg-muted);">Joshua Tree Health is an equal-opportunity employer. We celebrate diversity and are committed to creating an inclusive environment for all employees.</p>
    </div>
  </section>

</main>

<?php get_footer(); ?>
