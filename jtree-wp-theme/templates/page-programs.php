<?php
/**
 * Template Name: Programs
 *
 * @package JTreeHealth
 */
defined('ABSPATH') || exit;
get_header();

?>

<main id="main">

  <section class="section" style="padding-top: 64px; padding-bottom: 24px;">
    <div class="container" style="max-width: 880px;">
      <span class="jth-eyebrow" style="display:inline-block; margin-bottom: 12px;">Our programs</span>
      <h1 class="jth-display-l" style="font-size: clamp(40px, 5vw, 56px); margin: 0 0 20px;">Three levels of care, all <em class="jth-emph">outpatient.</em></h1>
      <p class="jth-body-l" style="margin: 0; max-width: 60ch;">No inpatient stay. No 30-day mandates. Programs that work around school, family, and the rest of a teenager's life.</p>
    </div>
  </section>

  <!-- PHP -->
  <section class="section" id="php">
    <div class="container">
      <div style="display:grid; grid-template-columns: 0.8fr 1.2fr; gap: 48px; align-items:start;">
        <aside>
          <span class="jth-pill jth-pill-sage">Most support</span>
          <h2 class="jth-h2" style="margin: 16px 0 12px;">Partial hospitalization</h2>
          <p class="jth-meta">Mon–Fri · 9 am – 3 pm</p>
          <p class="jth-meta">Ages 10–17</p>
          <a class="jth-btn jth-btn-primary" style="margin-top: 20px;" href="<?php echo esc_url(home_url('/admissions/')); ?>">Start admissions</a>
        </aside>
        <div>
          <h3 class="jth-h4" style="margin:0 0 8px;">Who it's for</h3>
          <p class="jth-body" style="margin:0 0 24px;">Teens whose mental-health needs make a typical school day untenable right now — frequent panic, persistent suicidal ideation, recent inpatient discharge, or a depression deep enough to keep them in bed.</p>

          <h3 class="jth-h4" style="margin:0 0 8px;">A day in PHP</h3>
          <ul style="margin:0 0 24px; padding-left:18px; line-height:1.7;">
            <li>Two DBT skills groups (mindfulness, emotion regulation, distress tolerance, interpersonal effectiveness)</li>
            <li>One process group with peers</li>
            <li>One individual session per week with a primary therapist</li>
            <li>Family session every other week</li>
            <li>Med-management consult on intake and as needed</li>
            <li>Academic support — your teen keeps up with school</li>
          </ul>

          <h3 class="jth-h4" style="margin:0 0 8px;">What we treat in PHP</h3>
          <div style="display:flex; flex-wrap: wrap; gap: 8px; margin-bottom: 24px;">
            <span class="jth-pill">Major depression</span>
            <span class="jth-pill">Anxiety + panic</span>
            <span class="jth-pill">OCD</span>
            <span class="jth-pill">Self-harm</span>
            <span class="jth-pill">Trauma</span>
            <span class="jth-pill">Eating concerns (non-acute)</span>
          </div>

          <p class="jth-body-s" style="margin:0;">Most insurance plans cover PHP. We verify coverage before your first day so there are no surprises.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- IOP -->
  <section class="section section-bg-cream-2" id="iop">
    <div class="container">
      <div style="display:grid; grid-template-columns: 0.8fr 1.2fr; gap: 48px; align-items:start;">
        <aside>
          <span class="jth-pill jth-pill-lime">After school</span>
          <h2 class="jth-h2" style="margin: 16px 0 12px;">Intensive outpatient</h2>
          <p class="jth-meta">Tue / Wed / Thu · 3 – 6 pm</p>
          <p class="jth-meta">Ages 10–17</p>
          <a class="jth-btn jth-btn-primary" style="margin-top: 20px;" href="<?php echo esc_url(home_url('/admissions/')); ?>">Start admissions</a>
        </aside>
        <div>
          <h3 class="jth-h4" style="margin:0 0 8px;">Who it's for</h3>
          <p class="jth-body" style="margin:0 0 24px;">Teens who can stay in school but need more than a weekly therapist. A stepping-down option after PHP, or a stepping-up option when weekly outpatient isn't holding.</p>

          <h3 class="jth-h4" style="margin:0 0 8px;">An IOP afternoon</h3>
          <ul style="margin:0 0 24px; padding-left:18px; line-height:1.7;">
            <li>Skills group rooted in DBT (3 hours, three days a week)</li>
            <li>Individual therapy weekly</li>
            <li>Family session every two weeks</li>
            <li>Optional med-management coordination with your existing prescriber</li>
          </ul>

          <h3 class="jth-h4" style="margin:0 0 8px;">What we treat in IOP</h3>
          <div style="display:flex; flex-wrap: wrap; gap: 8px;">
            <span class="jth-pill">Anxiety</span>
            <span class="jth-pill">Depression</span>
            <span class="jth-pill">ADHD + emotion dysregulation</span>
            <span class="jth-pill">Trauma</span>
            <span class="jth-pill">School avoidance</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Med mgmt -->
  <section class="section" id="medmgmt">
    <div class="container">
      <div style="display:grid; grid-template-columns: 0.8fr 1.2fr; gap: 48px; align-items:start;">
        <aside>
          <span class="jth-pill jth-pill-lavender">By appointment</span>
          <h2 class="jth-h2" style="margin: 16px 0 12px;">Medication management</h2>
          <p class="jth-meta">Telehealth + in person</p>
          <p class="jth-meta">Ages 10–17</p>
          <a class="jth-btn jth-btn-primary" style="margin-top: 20px;" href="<?php echo esc_url(home_url('/admissions/')); ?>">Start admissions</a>
        </aside>
        <div>
          <h3 class="jth-h4" style="margin:0 0 8px;">A psychiatrist who knows your teen</h3>
          <p class="jth-body" style="margin:0 0 24px;">If your teen is in PHP or IOP, our psychiatry team coordinates with the therapy team in real time. Outside our programs, we offer standalone medication management — no judgment, no rush, and a real explanation of every prescription.</p>

          <h3 class="jth-h4" style="margin:0 0 8px;">What this looks like</h3>
          <ul style="margin:0; padding-left:18px; line-height:1.7;">
            <li>60-minute initial evaluation; 25-minute follow-ups</li>
            <li>Coordination with your existing therapist or PCP</li>
            <li>Family included in decisions, with the teen's voice at the center</li>
            <li>Honest conversations about what medication can and can't do</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="cta-band">
    <div class="container">
      <h2>Not sure which level is right?</h2>
      <p>Tell us a little about what's going on. The admissions team will help you figure out the next step — even if that step isn't us.</p>
      <a class="jth-btn jth-btn-lime jth-btn-lg" href="<?php echo esc_url(home_url('/admissions/')); ?>">Start the Conversation</a>
    </div>
  </section>

</main>

<?php get_footer(); ?>
