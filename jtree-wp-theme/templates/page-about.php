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
    <img class="collage" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/collage-purple-grid-torn.png'); ?>" alt="" style="right:-20px; top:-20px; width: 220px; opacity:0.85;">
    <img class="twinkle" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/twinkle-outline-lavender.svg'); ?>" alt="" style="left:32%; top:18%; width:30px;">
    <div>
      <span class="jth-eyebrow">Our story</span>
      <h1 class="jth-display-l" style="font-size: clamp(40px, 5vw, 56px); margin: 14px 0 20px;">For my brother Josh.</h1>
      <p class="jth-body-l" style="margin: 0 0 14px; max-width: 56ch;">In 2022 my brother Josh died. He was 19. He was funny, stubborn, and a deeply good person who couldn't find care that took him seriously enough.</p>
      <p class="jth-body-l" style="margin: 0; max-width: 56ch;">I started JTree Health because the kind of place I wish he'd had didn't exist near us. So we made it.</p>
      <p class="jth-hand" style="margin-top: 24px; transform: rotate(-2deg);">— Gabriela</p>
    </div>
    <div class="about-portrait">
      <img src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/collage-torn-desert-photo.png'); ?>" alt="A Joshua tree in the high desert" style="width:100%; height:100%; object-fit:cover;">
    </div>
  </section>

  <!-- Brand-name story -->
  <section class="section section-bg-cream-2">
    <div class="container" style="max-width:880px;">
      <span class="jth-eyebrow">Why a Joshua tree</span>
      <h2 class="jth-h2" style="margin: 12px 0 20px;">A tree that grows in a shape entirely its own.</h2>
      <p class="jth-body-l" style="margin: 0 0 16px;">Joshua trees grow slowly, sideways as often as up, branching where the wind tells them to branch. Two trees in the same desert never look the same.</p>
      <p class="jth-body-l" style="margin: 0;">That's what we want for the teens in our care. Not a return to who they were before. A real, grounded version of who they're becoming — in whatever shape that turns out to be.</p>
    </div>
  </section>

  <!-- Team -->
  <section class="section">
    <div class="container">
      <header class="section-head">
        <span class="jth-eyebrow">Clinical team</span>
        <h2 class="jth-h2">Adolescent specialists, every one.</h2>
      </header>
      <div class="cards-3">
        <div class="program-card">
          <div style="width:72px; height:72px; border-radius: 9999px; background: var(--jth-pale-sage); border: 1px solid var(--jth-rule-strong); display:flex; align-items:center; justify-content:center; font-family: var(--font-editorial); font-size: 28px; color: var(--jth-deep-green);">GM</div>
          <h3 class="jth-h3">Gabriela Miranda, LCSW</h3>
          <p class="program-meta">Founder · Clinical Director</p>
          <p>15 years in adolescent DBT. Former director of a Triangle-area teen IOP.</p>
        </div>
        <div class="program-card">
          <div style="width:72px; height:72px; border-radius: 9999px; background: var(--jth-pale-lavender); border: 1px solid var(--jth-rule-strong); display:flex; align-items:center; justify-content:center; font-family: var(--font-editorial); font-size: 28px; color: var(--jth-deep-green);">MR</div>
          <h3 class="jth-h3">Marcus Reyes, MD</h3>
          <p class="program-meta">Child &amp; Adolescent Psychiatrist</p>
          <p>Triple-board-certified. Believes the teen has a vote in their own treatment.</p>
        </div>
        <div class="program-card">
          <div style="width:72px; height:72px; border-radius: 9999px; background: var(--jth-pale-lime); border: 1px solid var(--jth-rule-strong); display:flex; align-items:center; justify-content:center; font-family: var(--font-editorial); font-size: 28px; color: var(--jth-deep-green);">PO</div>
          <h3 class="jth-h3">Priya Okafor, LMFT</h3>
          <p class="program-meta">Family Therapy Lead</p>
          <p>Trauma-informed family work. Bilingual: English / Spanish.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Values -->
  <section class="section section-bg-dark" style="position:relative; overflow:hidden;">
    <img class="collage" src="<?php echo esc_url(JTREE_THEME_URI . '/assets/brand/collage-lime-brush.png'); ?>" alt="" style="left:-30px; top: 40px; width: 200px; opacity:0.7;">
    <div class="container">
      <header class="section-head">
        <span class="jth-eyebrow" style="color: var(--jth-lime-green);">What we believe</span>
        <h2 class="jth-h2">Care that respects intelligence.</h2>
      </header>
      <div class="cards-3">
        <div class="jth-card-dark" style="background: color-mix(in oklch, var(--jth-deep-green) 90%, white 10%); border: 1px solid color-mix(in oklch, var(--jth-cream) 16%, var(--jth-deep-green) 84%);">
          <h3 class="jth-h3" style="margin:0 0 10px;">Clinical excellence is the floor.</h3>
          <p style="margin:0; opacity:0.85;">DBT-informed care delivered by trained, supervised clinicians. CARF-accredited. Boring, in the best way.</p>
        </div>
        <div class="jth-card-dark" style="background: color-mix(in oklch, var(--jth-deep-green) 90%, white 10%); border: 1px solid color-mix(in oklch, var(--jth-cream) 16%, var(--jth-deep-green) 84%);">
          <h3 class="jth-h3" style="margin:0 0 10px;">Teens are not adults in waiting.</h3>
          <p style="margin:0; opacity:0.85;">We don't talk down. We don't manipulate. We treat your kid like the smart, skeptical person they are.</p>
        </div>
        <div class="jth-card-dark" style="background: color-mix(in oklch, var(--jth-deep-green) 90%, white 10%); border: 1px solid color-mix(in oklch, var(--jth-cream) 16%, var(--jth-deep-green) 84%);">
          <h3 class="jth-h3" style="margin:0 0 10px;">Families are part of the work.</h3>
          <p style="margin:0; opacity:0.85;">Healing happens in relationship. Parents and siblings are not bystanders here.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CARF -->
  <section class="section section-tight">
    <div class="container">
      <div style="display:flex; gap: 24px; align-items:center; padding: 28px; background: var(--jth-pale-sage); border-radius: var(--r-card);">
        <span class="carf-badge" style="border-color: var(--jth-deep-green); color: var(--jth-deep-green);">CARF Accredited</span>
        <p class="jth-body-s" style="margin:0; color: var(--jth-charcoal);">Joshua Tree Health is accredited by CARF International. CARF accreditation is an external standard of clinical quality, ethics, and outcomes — not a marketing badge.</p>
      </div>
    </div>
  </section>

  <section class="cta-band">
    <div class="container">
      <h2>Ready to talk?</h2>
      <p>One short form, then a real human calls you within a business day.</p>
      <a class="jth-btn jth-btn-lime jth-btn-lg" href="<?php echo esc_url(home_url('/admissions/')); ?>">Start the Conversation</a>
    </div>
  </section>

</main>

<?php get_footer(); ?>
