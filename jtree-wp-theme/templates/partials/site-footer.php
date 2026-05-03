<?php
/**
 * Partial: Site Footer
 *
 * @package JTreeHealth
 */

defined('ABSPATH') || exit;

$home = esc_url(home_url('/'));
?>

<footer class="footer">
  <div class="footer__grid">

    <div>
      <div class="footer__logo">jtree health<span>.</span></div>
      <p class="footer__tagline">Adolescent PHP &amp; IOP in Apex, NC. Named for a desert plant — and for a brother we lost too soon.</p>
      <div class="footer__crisis">
        <p class="footer__crisis-label">24/7 Crisis Line</p>
        <p class="footer__crisis-num">988</p>
      </div>
    </div>

    <div>
      <p class="footer__col-title">Programs</p>
      <ul class="footer__links">
        <li><a href="<?php echo $home; ?>programs/#php">PHP Program</a></li>
        <li><a href="<?php echo $home; ?>programs/#iop">IOP Program</a></li>
        <li><a href="<?php echo $home; ?>programs/#medmgmt">Medication Mgmt</a></li>
        <li><a href="<?php echo $home; ?>insurance/">Insurance</a></li>
      </ul>
    </div>

    <div>
      <p class="footer__col-title">About</p>
      <ul class="footer__links">
        <li><a href="<?php echo $home; ?>about/">Our Story</a></li>
        <li><a href="<?php echo $home; ?>about/#team">Clinical Team</a></li>
        <li><a href="<?php echo $home; ?>about/#carf">CARF Accreditation</a></li>
        <li><a href="<?php echo $home; ?>contact/">Careers</a></li>
      </ul>
    </div>

    <div>
      <p class="footer__col-title">Contact</p>
      <ul class="footer__links">
        <li><a href="<?php echo $home; ?>admissions/">Start Admissions</a></li>
        <li><a href="tel:9192764005">(919) 276-4005</a></li>
        <li><a href="<?php echo $home; ?>contact/">Apex, NC 27502</a></li>
      </ul>
    </div>

  </div>

  <div class="footer__bottom">
    <p class="footer__copy">&copy; <?php echo date('Y'); ?> JTree Health. All rights reserved.</p>
    <div class="footer__legal">
      <a href="<?php echo $home; ?>privacy/">Privacy Policy</a>
      <a href="<?php echo $home; ?>admissions/">Admissions</a>
      <a href="<?php echo $home; ?>crisis/">Crisis Help</a>
    </div>
  </div>
</footer>
