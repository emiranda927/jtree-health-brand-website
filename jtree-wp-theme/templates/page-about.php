<?php
/**
 * Template Name: About
 *
 * @package JTreeHealth
 */
defined('ABSPATH') || exit;
get_header();

?>

<main id="main">

  <!-- Hero -->
  <section class="section about-hero about-hero--composed">
    <img class="collage about-hero__collage" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/collage-purple-grid-torn.png'); ?>" alt="" width="220" height="220" loading="lazy" decoding="async" aria-hidden="true">
    <img class="twinkle about-hero__twinkle" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/twinkle-outline-lavender.svg'); ?>" alt="" width="30" height="30" loading="lazy" aria-hidden="true">
    <div>
      <span class="jth-eyebrow jth-reveal">&sect; 01 / Our story</span>
      <h1 class="jth-display-l jth-reveal jth-reveal--delay-1">For my brother Josh.</h1>
      <p class="jth-body-l jth-lede--narrow jth-mb-4 jth-reveal jth-reveal--delay-2">My brother Josh died in 2023, after years of fighting battles that started long before anyone helped him fight them.</p>
      <p class="jth-body-l jth-lede--narrow jth-mb-4 jth-reveal jth-reveal--delay-2">He found peace in Joshua Tree National Park, in the quiet of the desert, in the resilience of a tree that grows in some of the harshest conditions on earth.</p>
      <p class="jth-body-l jth-lede--narrow jth-reveal jth-reveal--delay-3">I started Joshua Tree Health for the kids who deserve real help earlier than he got it.</p>
      <p class="jth-hand jth-hand--tape jth-reveal jth-reveal--delay-3">&mdash; Gabriela</p>
    </div>
    <div class="about-portrait">
      <img class="about-portrait__img" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/collage-torn-desert-photo.png'); ?>" alt="A Joshua tree in the high desert" width="800" height="1000" loading="lazy" decoding="async">
    </div>
  </section>

  <!-- Brand-name story -->
  <section class="section section-bg-cream-2">
    <div class="container container--narrow">
      <span class="jth-eyebrow">&sect; 02 / Why a Joshua tree</span>
      <h2 class="jth-h2 jth-mb-5">A tree that grows in a shape entirely its own.</h2>
      <p class="jth-body-l jth-mb-4">Joshua trees grow slowly, sideways as often as up, branching where the wind tells them to branch. Two trees in the same desert never look the same.</p>
      <p class="jth-body-l jth-mb-0">That's what we want for the teens in our care. Not a return to who they were before. A real, grounded version of who they're becoming &mdash; in whatever shape that turns out to be.</p>
    </div>
  </section>

  <!-- Founder — Gabriela Miranda. Owner / operator, not a clinician.
       Establishes her role before the Clinical Team section so the division
       between business leadership and clinical leadership is clear. -->
  <section class="section section-bg-pale-lav">
    <div class="container container--prose">
      <span class="jth-eyebrow">&sect; 03 / Founder</span>
      <h2 class="jth-h2 jth-mb-5">Gabriela Miranda.</h2>
      <p class="jth-body-l jth-mb-4">Gabriela founded Joshua Tree Health to build the kind of care her brother Josh didn't have. She brings a background in healthcare investing, AI, and operations &mdash; an MBA from Stanford, a Master in Public Administration from Harvard Kennedy School, and a long-running focus on expanding access to mental-health care for underserved communities.</p>
      <p class="jth-body jth-text-muted">Gabriela owns the practice and runs the business side. The clinical work is led by Caitlyn and the team below.</p>
    </div>
  </section>

  <!-- Team — Clinical leadership + staff carrying through the JTree transition.
       Featured: Caitlyn (Executive Director). Side cards: Beth, Alayna, Corissa.
       Supervised interns acknowledged in a closing note. -->
  <section class="section">
    <div class="container">
      <header class="section-head">
        <span class="jth-eyebrow">&sect; 04 / Clinical team</span>
        <h2 class="jth-h2">Adolescent specialists, every one.</h2>
      </header>
      <div class="about-team">
        <article class="about-team__feature">
          <div class="about-team__avatar about-team__avatar--lg about-team__avatar--sage">CD</div>
          <h3 class="jth-h3 about-team__name">Caitlyn Dowdy, MS, LCMHCS</h3>
          <p class="program-meta about-team__role">Executive Director</p>
          <p class="about-team__bio">Over a decade in mental health. Trained in DBT, CBT, trauma-focused CBT, and Adlerian work. M.S. in Clinical Mental Health Counseling from Johns Hopkins. Caitlyn runs day-to-day clinical operations.</p>
          <p class="jth-hand jth-hand--tape-light about-team__hand">&mdash; usually the person who scopes your teen's care plan.</p>
        </article>
        <div class="about-team__side">
          <article class="about-team__card">
            <div class="about-team__avatar about-team__avatar--lavender">BB</div>
            <h3 class="jth-h4 about-team__name about-team__name--sm">Beth Bertram, LCSW</h3>
            <p class="program-meta about-team__role about-team__role--sm">Program Manager</p>
            <p class="jth-body-s about-team__bio about-team__bio--sm">Intensive DBT training; leads our multifamily groups. Background as a forensic interviewer and intensive in-home therapist. Affirms all identities.</p>
          </article>
          <article class="about-team__card">
            <div class="about-team__avatar about-team__avatar--lime">AW</div>
            <h3 class="jth-h4 about-team__name about-team__name--sm">Alayna West, MA, LCMHCA</h3>
            <p class="program-meta about-team__role about-team__role--sm">Mental Health Therapist</p>
            <p class="jth-body-s about-team__bio about-team__bio--sm">DBT, CBT, trauma-informed care, mindfulness. Specializes in LGBTQIA+ affirming and neurodivergent-affirming care.</p>
          </article>
          <article class="about-team__card">
            <div class="about-team__avatar about-team__avatar--sage">CP</div>
            <h3 class="jth-h4 about-team__name about-team__name--sm">Corissa Phannenstill, BA</h3>
            <p class="program-meta about-team__role about-team__role--sm">Qualified Professional</p>
            <p class="jth-body-s about-team__bio about-team__bio--sm">Background in domestic-violence advocacy and crisis text counseling. Pursuing LCSW. Acts as a steady presence between sessions.</p>
          </article>
        </div>
      </div>
      <p class="jth-body-s about-team__interns">Our team also includes three supervised mental-health interns &mdash; Ashley Bass-Mitchell, Analee Dubbs, and Katlyn Jones &mdash; working under the licensed clinicians above.</p>
    </div>
  </section>

  <!-- Three commitments — Operational framing per approved copy doc.
       Reads as what we DO, not what we BELIEVE. Voice flourishes preserved. -->
  <section class="section section-bg-dark about-values-section about-values-section--composed">
    <img class="collage about-values-section__collage" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/collage-lime-brush.png'); ?>" alt="" width="320" height="160" loading="lazy" decoding="async" aria-hidden="true">
    <div class="container">
      <header class="about-values__head">
        <span class="jth-eyebrow">&sect; 05 / Three commitments</span>
        <h2>How we work with families.</h2>
      </header>
      <div class="about-values__grid">
        <article class="about-values__card">
          <span class="about-values__num">01</span>
          <p class="about-values__statement">We <em>assess honestly.</em></p>
          <p class="about-values__body">We tell you what we see, including when our program isn't the right fit for your teen. No upsells. Boring, in the best way.</p>
        </article>
        <article class="about-values__card">
          <span class="about-values__num">02</span>
          <p class="about-values__statement">We <em>recommend what's right.</em></p>
          <p class="about-values__body">If your teen would be better served by a different level of care or a different clinician, we'll say so and point you there. We don't talk down to teens; we don't manipulate them.</p>
          <p class="about-values__aside">they notice everything.</p>
        </article>
        <article class="about-values__card">
          <span class="about-values__num">03</span>
          <p class="about-values__statement">We <em>stay alongside.</em></p>
          <p class="about-values__body">Healing happens in relationship. Whether your teen is here 6 weeks or 6 months, parents and siblings are part of the work, not bystanders.</p>
        </article>
      </div>
    </div>
  </section>

  <!-- CARF — Editorial accreditation block per the design system. -->
  <section class="section section-tight">
    <div class="container">
      <div class="carf-aside">
        <div class="carf-aside__rail">
          <span class="jth-eyebrow">&sect; 06 / Accreditation</span>
          <span class="carf-aside__mark">CARF</span>
        </div>
        <div class="carf-aside__body">
          <p class="carf-aside__statement">Joshua Tree Health is accredited by <em>CARF International.</em></p>
          <p class="carf-aside__caption">CARF accreditation is an external standard of clinical quality, ethics, and outcomes. Not a marketing badge.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="cta-band">
    <div class="container">
      <h2>Ready to talk?</h2>
      <p>One short form, then a real human calls you back within 2 to 4 business hours.</p>
      <a class="jth-btn jth-btn-secondary jth-btn-lg jth-btn-arrow" href="<?php echo esc_url(home_url('/admissions/')); ?>">
        Start the Conversation
        <span class="jth-btn-arrow__icon" aria-hidden="true">
          <svg viewBox="0 0 14 14" fill="none" focusable="false">
            <path d="M3.5 7h7M7 3.5l3.5 3.5L7 10.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </span>
      </a>
    </div>
  </section>

</main>

<?php get_footer(); ?>
