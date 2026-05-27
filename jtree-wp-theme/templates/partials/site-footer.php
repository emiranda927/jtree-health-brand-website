<?php
/**
 * Partial · Site footer.
 *
 * @package JTreeHealth
 */
defined('ABSPATH') || exit;

$home  = esc_url(home_url('/'));
$brand = JTREE_THEME_URI . '/assets/brand';
?>
<footer class="site-footer">
  <div class="footer-grid">
    <div class="footer-brand">
      <img
        src="<?php echo esc_url($brand . '/logo-inverted-cream.png'); ?>"
        alt="Joshua Tree Health"
        width="200" height="88" loading="lazy" decoding="async">
      <p>Adolescent PHP &amp; IOP in Apex, NC. Named for a desert plant &mdash; and for a brother we lost too soon.</p>
      <span class="carf-badge">CARF Accredited</span>
    </div>
    <div class="footer-col">
      <h4>Programs</h4>
      <ul>
        <li><a href="<?php echo $home; ?>programs/#iop">Intensive outpatient</a></li>
        <li><a href="<?php echo $home; ?>programs/#php">Partial hospitalization <span class="footer-soon">(coming soon)</span></a></li>
        <li><a href="<?php echo $home; ?>what-we-treat/">What we treat</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>For families</h4>
      <ul>
        <li><a href="<?php echo $home; ?>for-parents/">For Parents</a></li>
        <li><a href="<?php echo $home; ?>for-teens/">For Teens</a></li>
        <li><a href="<?php echo $home; ?>insurance/">Insurance</a></li>
        <li><a href="<?php echo $home; ?>admissions/">Start the Conversation</a></li>
        <li><a href="<?php echo $home; ?>careers/">Careers</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Reach us</h4>
      <ul>
        <li><a href="tel:9193355053">(919) 335-5053</a></li>
        <li>800 West Williams St., STE 203<br>Apex, NC 27502</li>
        <li>PHP: Mon–Fri 9–3</li>
        <li>IOP: in-person + virtual, multiple times/week</li>
        <li><a href="<?php echo $home; ?>crisis/">Crisis resources</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <span>&copy; <?php echo esc_html(date('Y')); ?> Joshua Tree Health. All rights reserved.</span>
    <span class="footer-legal">
      <a href="<?php echo $home; ?>privacy/">Privacy</a>
      <a href="<?php echo $home; ?>accessibility/">Accessibility</a>
    </span>
  </div>
</footer>
