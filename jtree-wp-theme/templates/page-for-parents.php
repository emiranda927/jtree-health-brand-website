<?php
/**
 * Template Name: For Parents
 *
 * @package JTreeHealth
 */
defined('ABSPATH') || exit;
get_header();
?>

<main id="main">

  <section class="section" style="padding-top: 64px; padding-bottom: 24px;">
    <div class="container" style="max-width: 880px;">
      <span class="jth-eyebrow" style="display:inline-block; margin-bottom: 12px;">For parents and caregivers</span>
      <h1 class="jth-display-l" style="font-size: clamp(40px, 5vw, 56px); margin: 0 0 20px;">You don't have to figure this out <em class="jth-emph">alone.</em></h1>
      <p class="jth-body-l" style="margin: 0; max-width: 60ch;">Most families come to us already exhausted. Outpatient therapy isn't holding. School is harder than it should be. The teen you love is still struggling, and you're running out of ideas.</p>
    </div>
  </section>

  <section class="section">
    <div class="container" style="max-width: 880px;">
      <h2 class="jth-h2" style="margin: 0 0 16px;">What to expect from us</h2>
      <ul style="font-size: 18px; line-height: 1.7; padding-left: 20px; margin: 0 0 24px;">
        <li><strong>2 to 4 business hours to a real conversation.</strong> An admissions clinician calls you, not a scheduler.</li>
        <li><strong>No surprise costs.</strong> We verify insurance before your teen's first day. If we're not in network with your plan, we'll tell you up front.</li>
        <li><strong>You stay in the loop.</strong> Family sessions are part of every program, not an add-on.</li>
        <li><strong>Honesty about fit.</strong> If we're not the right level of care, we'll tell you who is.</li>
      </ul>
      <p class="jth-body" style="margin: 0 0 28px;">If you're closer to a crisis than a question, call <strong>988</strong> right now. They are kind, fast, and they answer. We'll be here when you're ready.</p>
      <a class="jth-btn jth-btn-primary jth-btn-lg" href="<?php echo esc_url(home_url('/admissions/')); ?>">Start the Conversation</a>
    </div>
  </section>

  <section class="section section-bg-cream-2">
    <div class="container" style="max-width: 760px;">
      <span class="jth-eyebrow" style="display:inline-block; margin-bottom: 12px;">Where you fit in</span>
      <h2 class="jth-h2" style="margin: 0 0 16px;">Most fights with your teen aren't about the topic. They're about the pattern.</h2>
      <p class="jth-body-l" style="margin: 0 0 16px;">One of the modules in our 12-week IOP is called <em>Walking the Middle Path</em>. A full week of it is dedicated to parent-teen escalation patterns and how to repair after a rupture &mdash; because that loop is what most families are actually stuck in, not the curfew or the homework or the phone.</p>
      <p class="jth-body-l" style="margin: 0 0 16px;">We teach your teen to notice the early signs in their own body that escalation is starting. We also teach them validation, repair, and how to stay regulated when a parent is dysregulated. Then we bring you into family sessions every two weeks to practice the other side of that loop.</p>
      <p class="jth-body-l" style="margin: 0;">You don't have to be the perfect parent. You just have to be in the room.</p>
    </div>
  </section>

  <section class="section">
    <div class="container" style="max-width: 760px;">
      <span class="jth-eyebrow" style="display:inline-block; margin-bottom: 12px;">What your teen actually learns</span>
      <h2 class="jth-h2" style="margin: 0 0 16px;">Twelve weeks of skills, in plain English.</h2>
      <p class="jth-body" style="margin: 0 0 16px;">The IOP follows a 12-week arc rooted in Dialectical Behavior Therapy (DBT) and polyvagal theory. It builds on itself, week by week:</p>
      <ul style="font-size: 18px; line-height: 1.7; padding-left: 20px; margin: 0 0 24px;">
        <li><strong>Weeks 1&ndash;2</strong> &middot; Understanding why their nervous system reacts the way it does. Mindfulness as a starting skill, not a buzzword.</li>
        <li><strong>Weeks 3&ndash;4</strong> &middot; Distress tolerance: what to do in the moment a panic spiral or an urge to self-harm hits. (TIPP, self-soothe, radical acceptance.)</li>
        <li><strong>Weeks 5&ndash;7</strong> &middot; Emotion regulation: catching feelings earlier, doing the opposite of an unhelpful urge, taking care of sleep and food because they affect mood.</li>
        <li><strong>Weeks 8&ndash;9</strong> &middot; Interpersonal effectiveness: how to ask for what you need, how to say no, how to repair a friendship after conflict.</li>
        <li><strong>Weeks 10&ndash;12</strong> &middot; Walking the middle path, including the parent-teen week described above.</li>
        <li><strong>Graduation</strong> &middot; A personal regulation plan (the early warning signs and skills that work for <em>this</em> teen) and a letter to their future self.</li>
      </ul>
      <p class="jth-body-s" style="margin: 0; color: var(--jth-fg-muted);">If a skill name sounds clinical, that's because it is. The reason we name them is so your teen can come home and tell you "I used TIPP today" &mdash; and you can know what they mean.</p>
    </div>
  </section>

  <section class="cta-band">
    <div class="container">
      <h2>Questions before you fill out a form?</h2>
      <p>Call us. A clinician will pick up &mdash; or call you back the same business day.</p>
      <a class="jth-btn jth-btn-secondary jth-btn-lg" href="tel:9193355053">(919) 335-5053</a>
    </div>
  </section>

</main>

<?php get_footer(); ?>
