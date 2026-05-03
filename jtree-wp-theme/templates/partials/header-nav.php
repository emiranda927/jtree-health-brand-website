<?php
/**
 * Partial: Header Navigation
 *
 * @package JTreeHealth
 */

defined('ABSPATH') || exit;

$home = esc_url(home_url('/'));
?>

<nav class="nav" aria-label="Main navigation" id="jtree-nav">
  <div class="nav__inner">

    <a href="<?php echo $home; ?>" class="nav__logo" aria-label="JTree Health — Home">
      jtree health<span>.</span>
    </a>

    <ul class="nav__links" id="jtree-nav-links">

      <li class="nav__item nav__item--has-dropdown">
        <a href="<?php echo $home; ?>programs/" class="nav__link">Programs</a>
        <ul class="nav__dropdown">
          <li><a href="<?php echo $home; ?>programs/#php">PHP Program</a></li>
          <li><a href="<?php echo $home; ?>programs/#iop">IOP Program</a></li>
          <li><a href="<?php echo $home; ?>programs/#medmgmt">Medication Management</a></li>
        </ul>
      </li>

      <li class="nav__item nav__item--has-dropdown">
        <a href="<?php echo $home; ?>for-parents/" class="nav__link">Who We Help</a>
        <ul class="nav__dropdown">
          <li><a href="<?php echo $home; ?>for-teens/">For Teens</a></li>
          <li><a href="<?php echo $home; ?>for-parents/">For Parents</a></li>
        </ul>
      </li>

      <li class="nav__item nav__item--has-dropdown">
        <a href="<?php echo $home; ?>what-we-treat/" class="nav__link">Conditions</a>
        <ul class="nav__dropdown">
          <li><a href="<?php echo $home; ?>what-we-treat/#anxiety">Anxiety</a></li>
          <li><a href="<?php echo $home; ?>what-we-treat/#depression">Depression</a></li>
          <li><a href="<?php echo $home; ?>what-we-treat/#ocd">OCD</a></li>
          <li><a href="<?php echo $home; ?>what-we-treat/#adhd">ADHD</a></li>
          <li><a href="<?php echo $home; ?>what-we-treat/#trauma">Trauma &amp; PTSD</a></li>
          <li><a href="<?php echo $home; ?>what-we-treat/#self-harm">Self-Harm</a></li>
        </ul>
      </li>

      <li class="nav__item">
        <a href="<?php echo $home; ?>about/" class="nav__link">About</a>
      </li>

      <li class="nav__item">
        <a href="<?php echo $home; ?>insurance/" class="nav__link">Insurance</a>
      </li>

    </ul>

    <div class="nav__right">
      <a href="tel:9192764005" class="nav__phone">(919) 276-4005</a>
      <a href="<?php echo $home; ?>admissions/" class="btn btn--primary">Start Now</a>

      <button class="nav__toggle" id="jtree-nav-toggle" aria-label="Toggle navigation" aria-expanded="false">
        <span></span>
      </button>
    </div>

  </div>
</nav>

<script>
(function() {
  var toggle = document.getElementById('jtree-nav-toggle');
  var nav    = document.getElementById('jtree-nav');
  if (!toggle || !nav) return;

  toggle.addEventListener('click', function() {
    var isOpen = nav.classList.toggle('nav--open');
    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  });

  // Mobile: tap nav link with dropdown opens it
  nav.querySelectorAll('.nav__item--has-dropdown > .nav__link').forEach(function(link) {
    link.addEventListener('click', function(e) {
      if (window.innerWidth < 1024) {
        e.preventDefault();
        this.closest('.nav__item').classList.toggle('is-open');
      }
    });
  });
})();
</script>
