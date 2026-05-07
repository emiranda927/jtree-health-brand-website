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
      <p>Adolescent PHP and IOP mental-health care, rooted in Apex, NC.</p>
      <span class="carf-badge">CARF Accredited</span>
    </div>
    <div class="footer-col">
      <h4>Programs</h4>
      <ul>
        <li><a href="<?php echo $home; ?>programs/#php">Partial hospitalization</a></li>
        <li><a href="<?php echo $home; ?>programs/#iop">Intensive outpatient</a></li>
        <li><a href="<?php echo $home; ?>programs/#medmgmt">Medication management</a></li>
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
        <li><a href="tel:9192764005">(919) 276-4005</a></li>
        <li>Apex, NC 27502</li>
        <li>PHP: Mon–Fri 9–3</li>
        <li>IOP: Tue/Wed/Thu 3–6</li>
        <li><a href="<?php echo $home; ?>crisis/">Crisis resources</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <span>&copy; <?php echo esc_html(date('Y')); ?> Joshua Tree Health. All rights reserved.</span>
    <span>
      <a href="<?php echo $home; ?>privacy/" style="color:inherit; margin-right:18px;">Privacy</a>
      <a href="<?php echo $home; ?>accessibility/" style="color:inherit;">Accessibility</a>
    </span>
  </div>
</footer>
