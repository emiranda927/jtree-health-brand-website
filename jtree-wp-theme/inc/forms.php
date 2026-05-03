<?php
/**
 * JTree Health - Inquiry Form Markup
 *
 * Renders the inquiry form. Submission is handled by assets/js/form.js
 * which POSTs to api.jtreehealth.com (NOT WordPress).
 *
 * @package JTreeHealth
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Register the [jtree_inquiry_form] shortcode
 */
function jtree_inquiry_form_shortcode($atts) {
    ob_start();
    jtree_render_inquiry_form();
    return ob_get_clean();
}
add_shortcode('jtree_inquiry_form', 'jtree_inquiry_form_shortcode');

/**
 * Render the inquiry form markup
 */
function jtree_render_inquiry_form() {
    ?>
    <form id="jtree-inquiry-form" class="jtree-form" novalidate>
        <div class="jtree-form__header">
            <h3>Start the Conversation</h3>
            <p>Fill out this short form and we'll reach out within one business day.</p>
        </div>

        <div class="jtree-form__grid">
            <div class="jtree-form__field">
                <label for="parent_first_name" class="jtree-form__label">First Name <span class="jtree-form__required">*</span></label>
                <input
                    type="text"
                    id="parent_first_name"
                    name="parent_first_name"
                    required
                    minlength="1"
                    maxlength="50"
                    autocomplete="given-name"
                    placeholder="Your first name"
                    class="jtree-form__input"
                />
                <span class="jtree-form__error" data-field="parent_first_name"></span>
            </div>

            <div class="jtree-form__field">
                <label for="parent_last_name" class="jtree-form__label">Last Name <span class="jtree-form__required">*</span></label>
                <input
                    type="text"
                    id="parent_last_name"
                    name="parent_last_name"
                    required
                    minlength="1"
                    maxlength="50"
                    autocomplete="family-name"
                    placeholder="Your last name"
                    class="jtree-form__input"
                />
                <span class="jtree-form__error" data-field="parent_last_name"></span>
            </div>

            <div class="jtree-form__field">
                <label for="parent_email" class="jtree-form__label">Email <span class="jtree-form__required">*</span></label>
                <input
                    type="email"
                    id="parent_email"
                    name="parent_email"
                    required
                    autocomplete="email"
                    placeholder="your@email.com"
                    class="jtree-form__input"
                />
                <span class="jtree-form__error" data-field="parent_email"></span>
            </div>

            <div class="jtree-form__field">
                <label for="parent_phone" class="jtree-form__label">Phone <span class="jtree-form__required">*</span></label>
                <input
                    type="tel"
                    id="parent_phone"
                    name="parent_phone"
                    required
                    autocomplete="tel"
                    placeholder="(919) 555-1234"
                    class="jtree-form__input"
                />
                <span class="jtree-form__error" data-field="parent_phone"></span>
            </div>

            <div class="jtree-form__field">
                <label for="teen_age" class="jtree-form__label">Teen's Age <span class="jtree-form__required">*</span></label>
                <select id="teen_age" name="teen_age" required class="jtree-form__select">
                    <option value="">Select age</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                </select>
                <span class="jtree-form__error" data-field="teen_age"></span>
            </div>

            <div class="jtree-form__field">
                <label for="program_interest" class="jtree-form__label">Program Interest <span class="jtree-form__required">*</span></label>
                <select id="program_interest" name="program_interest" required class="jtree-form__select">
                    <option value="">Select program</option>
                    <option value="IOP">IOP (Intensive Outpatient)</option>
                    <option value="PHP">PHP (Partial Hospitalization)</option>
                    <option value="Not sure">Not sure yet</option>
                </select>
                <span class="jtree-form__error" data-field="program_interest"></span>
            </div>

            <div class="jtree-form__field">
                <label for="best_time_to_call" class="jtree-form__label">Best Time to Call <span class="jtree-form__required">*</span></label>
                <select id="best_time_to_call" name="best_time_to_call" required class="jtree-form__select">
                    <option value="">Select time</option>
                    <option value="Morning">Morning</option>
                    <option value="Afternoon">Afternoon</option>
                    <option value="Evening">Evening</option>
                </select>
                <span class="jtree-form__error" data-field="best_time_to_call"></span>
            </div>

            <div class="jtree-form__field">
                <label for="how_did_you_hear" class="jtree-form__label">How Did You Hear About Us?</label>
                <select id="how_did_you_hear" name="how_did_you_hear" class="jtree-form__select">
                    <option value="">Select (optional)</option>
                    <option value="Google">Google</option>
                    <option value="Referral">Referral</option>
                    <option value="Doctor">Doctor / Therapist</option>
                    <option value="School">School Counselor</option>
                    <option value="Other">Other</option>
                </select>
                <span class="jtree-form__error" data-field="how_did_you_hear"></span>
            </div>
        </div>

        <div class="jtree-form__consent">
            <label class="jtree-form__checkbox-label">
                <input
                    type="checkbox"
                    id="consent_contact"
                    name="consent_contact"
                    required
                    class="jtree-form__checkbox"
                />
                <span>I consent to being contacted by JTree Health about my inquiry. <span class="jtree-form__required">*</span></span>
            </label>
            <span class="jtree-form__error" data-field="consent_contact"></span>
        </div>

        <!-- Honeypot field - hidden from real users -->
        <div class="jtree-form__hp" aria-hidden="true">
            <input type="text" name="hp_field" id="hp_field" tabindex="-1" autocomplete="off" />
        </div>

        <div class="jtree-form__actions">
            <button type="submit" id="jtree-submit-btn" class="jtree-btn jtree-btn--primary jtree-btn--lg">
                <span class="jtree-btn__text">Submit Inquiry</span>
                <span class="jtree-btn__loading" style="display:none;">Submitting...</span>
            </button>
        </div>

        <div id="jtree-form-message" class="jtree-form__message" style="display:none;"></div>

        <p class="jtree-form__disclaimer">
            Your information is protected under HIPAA. We will never share your data with third parties.
            By submitting, you agree to our <a href="/privacy/">Privacy Policy</a>.
        </p>
    </form>
    <?php
}
