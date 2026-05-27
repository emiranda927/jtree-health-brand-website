<?php
/**
 * Joshua Tree Health · Inquiry form markup.
 *
 * Renders the inquiry form using design-system `.jth-input` / `.jth-select`
 * classes. Submission is handled by /assets/js/form.js, which POSTs to
 * https://api.jtreehealth.com/api/inquiry — never WordPress.
 *
 * @package JTreeHealth
 * @since 2.0.0
 */
defined('ABSPATH') || exit;

/**
 * Shortcode wrapper so the form can be embedded with [jtree_inquiry_form].
 */
function jtree_inquiry_form_shortcode($atts) {
    ob_start();
    jtree_render_inquiry_form();
    return ob_get_clean();
}
add_shortcode('jtree_inquiry_form', 'jtree_inquiry_form_shortcode');

/**
 * Render the inquiry form. Field names match the API contract exactly —
 * see jtree-form-api/lib/validate.ts.
 */
function jtree_render_inquiry_form() {
    ?>
    <form id="inquiry-form" class="jth-form" novalidate>

      <!-- Honeypot — hidden from real users, bot-fill triggers silent success -->
      <div class="jth-hp" aria-hidden="true">
        <label>Leave this field empty<input type="text" name="hp_field" tabindex="-1" autocomplete="off"></label>
      </div>

      <!-- session_id is set by form.js on load so partials and the eventual
           full submission can be correlated by the admissions team. -->
      <input type="hidden" name="session_id" id="session_id" value="">

      <div class="form-row">
        <div>
          <label class="jth-field-label" for="parent_first_name">Your first name</label>
          <input class="jth-input" id="parent_first_name" name="parent_first_name" type="text"
                 autocomplete="given-name" aria-describedby="err-parent_first_name" required minlength="1" maxlength="50">
          <span class="jth-field-error" id="err-parent_first_name" role="alert"></span>
        </div>
        <div>
          <label class="jth-field-label" for="parent_last_name">Last name</label>
          <input class="jth-input" id="parent_last_name" name="parent_last_name" type="text"
                 autocomplete="family-name" aria-describedby="err-parent_last_name" required minlength="1" maxlength="50">
          <span class="jth-field-error" id="err-parent_last_name" role="alert"></span>
        </div>
      </div>

      <div class="form-row">
        <div>
          <label class="jth-field-label" for="parent_email">Email</label>
          <input class="jth-input" id="parent_email" name="parent_email" type="email"
                 autocomplete="email" aria-describedby="err-parent_email" required>
          <span class="jth-field-error" id="err-parent_email" role="alert"></span>
        </div>
        <div>
          <label class="jth-field-label" for="parent_phone">Phone</label>
          <input class="jth-input" id="parent_phone" name="parent_phone" type="tel"
                 autocomplete="tel" placeholder="(919) 555-1234"
                 aria-describedby="err-parent_phone" required>
          <span class="jth-field-error" id="err-parent_phone" role="alert"></span>
        </div>
      </div>

      <div class="form-row">
        <div>
          <label class="jth-field-label" for="teen_age">Your teen's age</label>
          <select class="jth-select" id="teen_age" name="teen_age"
                  aria-describedby="err-teen_age" required>
            <option value="">Select an age</option>
            <?php for ($age = 10; $age <= 17; $age++) : ?>
              <option value="<?php echo esc_attr($age); ?>"><?php echo esc_html($age); ?></option>
            <?php endfor; ?>
          </select>
          <span class="jth-field-error" id="err-teen_age" role="alert"></span>
        </div>
        <div>
          <label class="jth-field-label" for="program_interest">Program you're considering</label>
          <select class="jth-select" id="program_interest" name="program_interest"
                  aria-describedby="err-program_interest" required>
            <option value="">Select a program</option>
            <option value="IOP">IOP &mdash; Intensive outpatient</option>
            <option value="PHP">PHP &mdash; Partial hospitalization (coming soon)</option>
            <option value="Not sure">Not sure yet</option>
          </select>
          <span class="jth-field-error" id="err-program_interest" role="alert"></span>
        </div>
      </div>

      <div class="form-row">
        <div>
          <label class="jth-field-label" for="best_time_to_call">Best time to call</label>
          <select class="jth-select" id="best_time_to_call" name="best_time_to_call"
                  aria-describedby="err-best_time_to_call" required>
            <option value="">Select a time</option>
            <option value="Morning">Morning</option>
            <option value="Afternoon">Afternoon</option>
            <option value="Evening">Evening</option>
          </select>
          <span class="jth-field-error" id="err-best_time_to_call" role="alert"></span>
        </div>
        <div>
          <label class="jth-field-label" for="how_did_you_hear">How did you find us?
            <span class="jth-field-optional">(optional)</span>
          </label>
          <select class="jth-select" id="how_did_you_hear" name="how_did_you_hear">
            <option value="">Select one</option>
            <option value="Google">Google</option>
            <option value="Referral">Referral</option>
            <option value="Doctor">Doctor / therapist</option>
            <option value="School">School counselor</option>
            <option value="Other">Other</option>
          </select>
        </div>
      </div>

      <div class="form-row solo">
        <label class="consent-row">
          <input type="checkbox" name="consent_contact" value="true"
                 aria-describedby="err-consent_contact" required>
          <span>It's okay to contact me by phone or email about my inquiry. I understand that submitting this form is not creating a clinical relationship.</span>
        </label>
        <span class="jth-field-error" id="err-consent_contact" role="alert"></span>
      </div>

      <!-- Turnstile widget mount point. form.js renders into this only when
           JTREE_CONFIG.turnstileSiteKey is set; otherwise the div stays empty
           and the API verifier falls open (no captcha gate). -->
      <div id="cf-turnstile" class="jth-turnstile" aria-live="polite"></div>

      <div class="form-actions">
        <button class="jth-btn jth-btn-primary jth-btn-lg" type="submit">Start the Conversation</button>
        <p class="form-privacy">Your information is protected. We will never share your data with third parties.</p>
        <div class="form-error-banner" role="alert"></div>
      </div>
    </form>
    <?php
}

/**
 * Render the careers application form. Posts to /api/careers/apply.
 * Field names match CareerApplicationSchema in jtree-form-api/lib/validate.ts.
 */
function jtree_render_careers_form() {
    $roles = apply_filters('jtree_career_roles', array(
        'Therapist (Associate or Fully Licensed)',
        'Qualified Professional (QP)',
        'Open application — other',
    ));
    ?>
    <form id="careers-form" class="jth-form" novalidate>

      <div class="jth-hp" aria-hidden="true">
        <label>Leave this field empty<input type="text" name="hp_field" tabindex="-1" autocomplete="off"></label>
      </div>

      <div class="form-row">
        <div>
          <label class="jth-field-label" for="applicant_first_name">First name</label>
          <input class="jth-input" id="applicant_first_name" name="applicant_first_name" type="text"
                 autocomplete="given-name" aria-describedby="err-applicant_first_name" required minlength="1" maxlength="50">
          <span class="jth-field-error" id="err-applicant_first_name" role="alert"></span>
        </div>
        <div>
          <label class="jth-field-label" for="applicant_last_name">Last name</label>
          <input class="jth-input" id="applicant_last_name" name="applicant_last_name" type="text"
                 autocomplete="family-name" aria-describedby="err-applicant_last_name" required minlength="1" maxlength="50">
          <span class="jth-field-error" id="err-applicant_last_name" role="alert"></span>
        </div>
      </div>

      <div class="form-row">
        <div>
          <label class="jth-field-label" for="applicant_email">Email</label>
          <input class="jth-input" id="applicant_email" name="applicant_email" type="email"
                 autocomplete="email" aria-describedby="err-applicant_email" required>
          <span class="jth-field-error" id="err-applicant_email" role="alert"></span>
        </div>
        <div>
          <label class="jth-field-label" for="applicant_phone">Phone</label>
          <input class="jth-input" id="applicant_phone" name="applicant_phone" type="tel"
                 autocomplete="tel" placeholder="(919) 555-1234"
                 aria-describedby="err-applicant_phone" required>
          <span class="jth-field-error" id="err-applicant_phone" role="alert"></span>
        </div>
      </div>

      <div class="form-row solo">
        <label class="jth-field-label" for="role_interest">Role you're interested in</label>
        <select class="jth-select" id="role_interest" name="role_interest" required>
          <option value="">Select a role</option>
          <?php foreach ($roles as $role) : ?>
            <option value="<?php echo esc_attr($role); ?>"><?php echo esc_html($role); ?></option>
          <?php endforeach; ?>
        </select>
        <span class="jth-field-error" id="err-role_interest" role="alert"></span>
      </div>

      <div class="form-row solo">
        <label class="jth-field-label" for="resume_url">Resume / LinkedIn link
          <span class="jth-field-optional">(optional)</span>
        </label>
        <input class="jth-input" id="resume_url" name="resume_url" type="url"
               placeholder="https://" autocomplete="url"
               aria-describedby="err-resume_url">
        <span class="jth-field-error" id="err-resume_url" role="alert"></span>
      </div>

      <div class="form-row solo">
        <label class="jth-field-label" for="message">A few words about you
          <span class="jth-field-optional">(optional)</span>
        </label>
        <textarea class="jth-input" id="message" name="message" rows="4" maxlength="2000"
                  placeholder="What drew you to adolescent mental health, what you're looking for, anything you'd like us to know."></textarea>
      </div>

      <div class="form-row solo">
        <label class="consent-row">
          <input type="checkbox" name="consent_contact" value="true"
                 aria-describedby="err-consent_contact" required>
          <span>It's okay to contact me about this application.</span>
        </label>
        <span class="jth-field-error" id="err-consent_contact" role="alert"></span>
      </div>

      <div id="cf-turnstile-careers" class="jth-turnstile" aria-live="polite"></div>

      <div class="form-actions">
        <button class="jth-btn jth-btn-primary jth-btn-lg" type="submit">Send application</button>
        <p class="form-privacy">Replies come from <a href="mailto:careers@jtreehealth.com">careers@jtreehealth.com</a>. We don't share applications outside the hiring team.</p>
        <div class="form-error-banner" role="alert"></div>
      </div>
    </form>
    <?php
}
