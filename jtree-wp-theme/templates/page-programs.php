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
      <h1 class="jth-display-l" style="font-size: clamp(40px, 5vw, 56px); margin: 0 0 20px;">Outpatient care, <em class="jth-emph">around the rhythms of a teen's life.</em></h1>
      <p class="jth-body-l" style="margin: 0; max-width: 60ch;">Our Intensive Outpatient Program (IOP) is open today, running in-person and virtually across multiple time blocks each week. A Partial Hospitalization Program (PHP) is launching soon at Joshua Tree Health. Both are outpatient: no inpatient stay, no 30-day mandates.</p>
    </div>
  </section>

  <!-- § 01 / IOP — Live program. Reversed grid (content-then-aside). -->
  <section class="section section-bg-cream-2" id="iop">
    <div class="container">
      <div class="jth-grid-aside--reverse">
        <div>
          <span class="jth-eyebrow" style="display:inline-block; margin-bottom: 12px;">&sect; 01 / IOP</span>
          <h2 class="jth-h2" style="margin: 0 0 16px;">Intensive outpatient</h2>
          <p class="jth-body-l" style="margin:0 0 28px; max-width: 60ch;">A 12-week DBT and polyvagal program for teens who can stay in school but need more than a weekly therapist. A stepping-up option when weekly outpatient isn't holding, or a stepping-down option from a higher level of care.</p>

          <h3 class="jth-h4" style="margin:0 0 8px;">The 12-week arc, week by week</h3>
          <ul style="margin:0 0 24px; padding-left:18px; line-height:1.7;">
            <li><strong>Weeks 1&ndash;2</strong> &middot; Foundations &amp; nervous-system awareness; mindfulness and interoception</li>
            <li><strong>Weeks 3&ndash;4</strong> &middot; Distress tolerance (TIPP, self-soothe, radical acceptance)</li>
            <li><strong>Weeks 5&ndash;7</strong> &middot; Emotion regulation (PLEASE, opposite action, check the facts, self-compassion)</li>
            <li><strong>Weeks 8&ndash;9</strong> &middot; Interpersonal effectiveness (DEAR MAN, GIVE, FAST)</li>
            <li><strong>Weeks 10&ndash;12</strong> &middot; Walking the middle path (validation, parent-teen escalation patterns, repair after rupture)</li>
            <li><strong>Graduation</strong> &middot; A personal regulation plan and a letter to the future self</li>
          </ul>

          <h3 class="jth-h4" style="margin:0 0 8px;">The weekly schedule</h3>
          <p class="jth-body" style="margin:0 0 12px;">Each teen attends three group blocks per week, picked from this rotation so the schedule fits the rest of their life:</p>
          <ul style="margin:0 0 24px; padding-left:18px; line-height:1.7;">
            <li><strong>Monday</strong> &middot; In-person, 4&ndash;7 PM (Apex)</li>
            <li><strong>Tuesday</strong> &middot; Virtual morning 9 AM&ndash;12 PM <em>or</em> in-person afternoon 4&ndash;7 PM</li>
            <li><strong>Thursday</strong> &middot; Virtual morning 9 AM&ndash;12 PM <em>or</em> in-person afternoon 4&ndash;7 PM</li>
            <li><strong>Saturday</strong> &middot; Virtual morning 9 AM&ndash;12 PM</li>
            <li><strong>Wednesday + Friday</strong> &middot; No groups; reserved for individual + family sessions</li>
          </ul>

          <h3 class="jth-h4" style="margin:0 0 8px;">What a group block looks like</h3>
          <p class="jth-body" style="margin:0 0 12px;">Every session follows the same predictable arc, so your teen always knows what's coming next:</p>
          <ol style="margin:0 0 24px; padding-left:18px; line-height:1.7;">
            <li>Check-in and diary cards</li>
            <li>Opening mindfulness (grounding breath or a mindful walk)</li>
            <li>Psychoeducation on the day's skill</li>
            <li>Interactive skill practice</li>
            <li>Processing and integration</li>
            <li>Group activity</li>
            <li>Check-out and diary cards</li>
          </ol>

          <h3 class="jth-h4" style="margin:0 0 8px;">Plus, outside of group</h3>
          <ul style="margin:0 0 24px; padding-left:18px; line-height:1.7;">
            <li>Individual therapy weekly (Wednesday or Friday)</li>
            <li>Family session every two weeks</li>
            <li>Nervous-system regulation work, including the Safe and Sound Protocol (SSP) when indicated</li>
          </ul>

          <h3 class="jth-h4" style="margin:0 0 8px;">What we treat in IOP</h3>
          <div class="jth-row-pills">
            <span class="jth-pill">Anxiety</span>
            <span class="jth-pill">Depression</span>
            <span class="jth-pill">PTSD &amp; trauma</span>
            <span class="jth-pill">OCD</span>
            <span class="jth-pill">ADHD + emotion dysregulation</span>
            <span class="jth-pill">Self-harm</span>
            <span class="jth-pill">Autism Level 1</span>
            <span class="jth-pill">School avoidance</span>
          </div>
        </div>
        <aside>
          <span class="jth-pill jth-pill-lime">Open today</span>
          <p class="jth-meta" style="margin-top: 16px;">In-person + virtual</p>
          <p class="jth-meta">3 group blocks/week</p>
          <p class="jth-meta">12 weeks &middot; Ages 10&ndash;17</p>
          <a class="jth-btn jth-btn-primary" style="margin-top: 20px;" href="<?php echo esc_url(home_url('/admissions/')); ?>">Start the Conversation</a>
        </aside>
      </div>
    </div>
  </section>

  <!-- § 02 / PHP — Coming soon. Aside-then-content. Higher acuity than IOP. -->
  <section class="section" id="php">
    <div class="container">
      <div class="jth-grid-aside">
        <aside>
          <span class="jth-eyebrow" style="display:inline-block; margin-bottom: 12px;">&sect; 02 / PHP</span>
          <span class="jth-pill" style="background:var(--jth-sunflower); color:var(--jth-charcoal);">Coming soon</span>
          <h2 class="jth-h2" style="margin: 16px 0 12px;">Partial hospitalization</h2>
          <p class="jth-meta">Mon&ndash;Fri &middot; 9 am &ndash; 3 pm</p>
          <p class="jth-meta">Ages 10&ndash;17</p>
          <a class="jth-btn jth-btn-secondary" style="margin-top: 20px;" href="<?php echo esc_url(home_url('/admissions/')); ?>">Get on the PHP list</a>
        </aside>
        <div>
          <h3 class="jth-h4" style="margin:0 0 8px;">Who it's for</h3>
          <p class="jth-body" style="margin:0 0 24px;">Teens whose mental-health needs make a typical school day untenable right now: frequent panic, suicidal ideation without an active plan, recent inpatient discharge, or a depression deep enough to keep them in bed.</p>

          <h3 class="jth-h4" style="margin:0 0 8px;">A day in PHP</h3>
          <ul style="margin:0 0 24px; padding-left:18px; line-height:1.7;">
            <li>Two DBT skills groups (mindfulness, emotion regulation, distress tolerance, interpersonal effectiveness)</li>
            <li>One process group with peers</li>
            <li>One individual session per week with a primary therapist</li>
            <li>Family session every other week</li>
            <li>Nervous-system regulation work, including the Safe and Sound Protocol (SSP) when clinically indicated</li>
            <li>Academic support so your teen keeps up with school</li>
          </ul>

          <h3 class="jth-h4" style="margin:0 0 8px;">What we'll treat in PHP</h3>
          <div class="jth-row-pills" style="margin-bottom: 24px;">
            <span class="jth-pill">Major depression</span>
            <span class="jth-pill">Anxiety + panic</span>
            <span class="jth-pill">OCD</span>
            <span class="jth-pill">Self-harm</span>
            <span class="jth-pill">Trauma</span>
            <span class="jth-pill">Eating concerns (non-acute)</span>
          </div>

          <p class="jth-body-s" style="margin:0;">Want to be the first to know when PHP opens? Start the conversation and we'll add you to the list.</p>
        </div>
      </div>
    </div>
  </section>

  <?php
    set_query_var('faq_slug', 'programs');
    set_query_var('faq_eyebrow', 'Common questions');
    set_query_var('faq_title', 'About our programs');
    set_query_var('faq_intro', '');
    get_template_part('templates/partials/faq');
  ?>

  <!-- CTA -->
  <section class="cta-band">
    <div class="container">
      <h2>Not sure which level is right?</h2>
      <p>Tell us a little about what's going on. The admissions team will help you figure out the next step — even if that step isn't us.</p>
      <a class="jth-btn jth-btn-secondary jth-btn-lg" href="<?php echo esc_url(home_url('/admissions/')); ?>">Start the Conversation</a>
    </div>
  </section>

</main>

<?php get_footer(); ?>
