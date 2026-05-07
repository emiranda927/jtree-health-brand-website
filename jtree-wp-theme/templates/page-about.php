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
  <section class="section about-hero" style="position:relative; overflow:hidden;">
    <img class="collage" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/collage-purple-grid-torn.png'); ?>" alt="" width="220" height="220" loading="lazy" decoding="async" aria-hidden="true" style="right:-20px; top:-20px; width: 220px; opacity:0.85;">
    <img class="twinkle" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/twinkle-outline-lavender.svg'); ?>" alt="" width="30" height="30" loading="lazy" aria-hidden="true" style="left:32%; top:18%; width:30px;">
    <div>
      <span class="jth-eyebrow">§ 01 / Our story</span>
      <h1 class="jth-display-l" style="font-size: clamp(40px, 5vw, 56px); margin: 14px 0 20px;">For my brother Josh.</h1>
      <p class="jth-body-l" style="margin: 0 0 14px; max-width: 56ch;">In 2022 my brother Josh died. He was 19. He was funny, stubborn, and a deeply good person who couldn't find care that took him seriously enough.</p>
      <p class="jth-body-l" style="margin: 0; max-width: 56ch;">I started JTree Health because the kind of place I wish he'd had didn't exist near us. So we made it.</p>
      <p class="jth-hand" style="margin-top: 24px; transform: rotate(-2deg);">— Gabriela</p>
    </div>
    <div class="about-portrait">
      <img src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/collage-torn-desert-photo.png'); ?>" alt="A Joshua tree in the high desert" width="800" height="1000" loading="lazy" decoding="async" style="width:100%; height:100%; object-fit:cover;">
    </div>
  </section>

  <!-- Brand-name story -->
  <section class="section section-bg-cream-2">
    <div class="container" style="max-width:880px;">
      <span class="jth-eyebrow">§ 02 / Why a Joshua tree</span>
      <h2 class="jth-h2" style="margin: 12px 0 20px;">A tree that grows in a shape entirely its own.</h2>
      <p class="jth-body-l" style="margin: 0 0 16px;">Joshua trees grow slowly, sideways as often as up, branching where the wind tells them to branch. Two trees in the same desert never look the same.</p>
      <p class="jth-body-l" style="margin: 0;">That's what we want for the teens in our care. Not a return to who they were before. A real, grounded version of who they're becoming — in whatever shape that turns out to be.</p>
    </div>
  </section>

  <!-- Team — Vary-The-Trio: Founder card spans 2 cols + has a margin note;
       Marcus & Priya are smaller cards stacked beside it on desktop. -->
  <section class="section">
    <div class="container">
      <header class="section-head">
        <span class="jth-eyebrow">§ 03 / Clinical team</span>
        <h2 class="jth-h2">Adolescent specialists, every one.</h2>
      </header>
      <div class="about-team">
        <article class="about-team__feature">
          <div class="about-team__avatar about-team__avatar--lg" style="background: var(--jth-pale-sage);">GM</div>
          <h3 class="jth-h3" style="margin: 16px 0 4px;">Gabriela Miranda, LCSW</h3>
          <p class="program-meta" style="margin: 0 0 14px;">Founder · Clinical Director</p>
          <p style="margin: 0 0 14px; max-width: 50ch;">15 years in adolescent DBT. Former director of a Triangle-area teen IOP. Built JTree because the kind of care she wished her brother Josh had didn't exist near here.</p>
          <p class="jth-hand" style="transform: rotate(-1.5deg); margin: 12px 0 0;">— she's the one who'll call you back.</p>
        </article>
        <div class="about-team__side">
          <article class="about-team__card">
            <div class="about-team__avatar" style="background: var(--jth-pale-lavender);">MR</div>
            <h3 class="jth-h4" style="margin: 12px 0 2px;">Marcus Reyes, MD</h3>
            <p class="program-meta" style="margin: 0 0 8px;">Child &amp; Adolescent Psychiatrist</p>
            <p class="jth-body-s" style="margin: 0;">Triple-board-certified. Believes the teen has a vote in their own treatment.</p>
          </article>
          <article class="about-team__card">
            <div class="about-team__avatar" style="background: var(--jth-pale-lime);">PO</div>
            <h3 class="jth-h4" style="margin: 12px 0 2px;">Priya Okafor, LMFT</h3>
            <p class="program-meta" style="margin: 0 0 8px;">Family Therapy Lead</p>
            <p class="jth-body-s" style="margin: 0;">Trauma-informed family work. Bilingual: English / Spanish.</p>
          </article>
        </div>
      </div>
    </div>
  </section>

  <!-- Values — three-up cards on dark green. Vary-The-Trio honored via the
       Caveat margin note on §02. -->
  <section class="section section-bg-dark about-values-section" style="position:relative; overflow:hidden;">
    <img class="collage" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/collage-lime-brush.png'); ?>" alt="" width="320" height="160" loading="lazy" decoding="async" aria-hidden="true" style="left:-30px; top: 40px; width: 200px; opacity:0.7;">
    <div class="container">
      <header class="about-values__head">
        <span class="jth-eyebrow">§ 04 / What we believe</span>
        <h2>Care that respects intelligence.</h2>
      </header>
      <div class="about-values__grid">
        <article class="about-values__card">
          <span class="about-values__num">01</span>
          <p class="about-values__statement">Clinical excellence is <em>the floor</em>, not the ceiling.</p>
          <p class="about-values__body">DBT-informed care delivered by trained, supervised clinicians. CARF-accredited. Boring, in the best way.</p>
        </article>
        <article class="about-values__card">
          <span class="about-values__num">02</span>
          <p class="about-values__statement">Teens are not <em>adults in waiting.</em></p>
          <p class="about-values__body">We don't talk down. We don't manipulate. We treat your kid like the smart, skeptical person they are.</p>
          <p class="about-values__aside">they notice everything.</p>
        </article>
        <article class="about-values__card">
          <span class="about-values__num">03</span>
          <p class="about-values__statement">Families are <em>part of the work.</em></p>
          <p class="about-values__body">Healing happens in relationship. Parents and siblings are not bystanders here.</p>
        </article>
      </div>
    </div>
  </section>

  <!-- CARF — Editorial accreditation block per the design system. -->
  <section class="section section-tight">
    <div class="container">
      <div class="carf-aside">
        <div class="carf-aside__rail">
          <span class="jth-eyebrow">§ 05 / Accreditation</span>
          <span class="carf-aside__mark">CARF</span>
        </div>
        <div class="carf-aside__body">
          <p class="carf-aside__statement">Joshua Tree Health is accredited by <em>CARF International.</em></p>
          <p class="carf-aside__caption">CARF accreditation is an external standard of clinical quality, ethics, and outcomes — not a marketing badge.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="cta-band">
    <div class="container">
      <h2>Ready to talk?</h2>
      <p>One short form, then a real human calls you within a business day.</p>
      <a class="jth-btn jth-btn-secondary jth-btn-lg" href="<?php echo esc_url(home_url('/admissions/')); ?>">Start the Conversation</a>
    </div>
  </section>

</main>

<?php get_footer(); ?>
