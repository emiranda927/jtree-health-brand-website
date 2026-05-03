/**
 * JTree Health - Inquiry Form Handler
 *
 * Vanilla JS. Posts to api.jtreehealth.com.
 * NO frameworks. NO jQuery.
 */

(function () {
  'use strict';

  const ENDPOINT = 'https://api.jtreehealth.com/api/inquiry';
  const THANK_YOU_URL = '/thank-you/';
  const PHONE_NUMBER = '(919) 276-4005';

  const form = document.getElementById('jtree-inquiry-form');
  if (!form) return;

  const submitBtn = document.getElementById('jtree-submit-btn');
  const btnText = submitBtn.querySelector('.jtree-btn__text');
  const btnLoading = submitBtn.querySelector('.jtree-btn__loading');
  const formMessage = document.getElementById('jtree-form-message');

  /**
   * Validation rules
   */
  const validators = {
    parent_first_name: function (v) {
      if (!v || v.trim().length === 0) return 'First name is required.';
      if (v.trim().length > 50) return 'First name must be 50 characters or fewer.';
      return '';
    },
    parent_last_name: function (v) {
      if (!v || v.trim().length === 0) return 'Last name is required.';
      if (v.trim().length > 50) return 'Last name must be 50 characters or fewer.';
      return '';
    },
    parent_email: function (v) {
      if (!v || v.trim().length === 0) return 'Email is required.';
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim())) return 'Please enter a valid email address.';
      return '';
    },
    parent_phone: function (v) {
      if (!v || v.trim().length === 0) return 'Phone number is required.';
      var digits = v.replace(/\D/g, '');
      if (digits.length < 10) return 'Please enter a valid phone number.';
      return '';
    },
    teen_age: function (v) {
      if (!v) return 'Please select your teen\'s age.';
      return '';
    },
    program_interest: function (v) {
      if (!v) return 'Please select a program.';
      return '';
    },
    best_time_to_call: function (v) {
      if (!v) return 'Please select a preferred time.';
      return '';
    },
    consent_contact: function (v, el) {
      if (!el.checked) return 'You must consent to be contacted.';
      return '';
    }
  };

  /**
   * Show error for a specific field
   */
  function showFieldError(fieldName, message) {
    var errorEl = form.querySelector('[data-field="' + fieldName + '"]');
    if (errorEl) {
      errorEl.textContent = message;
      errorEl.style.display = message ? 'block' : 'none';
    }
    var input = form.querySelector('[name="' + fieldName + '"]');
    if (input) {
      if (message) {
        input.classList.add('jtree-form__input--error');
      } else {
        input.classList.remove('jtree-form__input--error');
      }
    }
  }

  /**
   * Clear all errors
   */
  function clearErrors() {
    var errorEls = form.querySelectorAll('.jtree-form__error');
    for (var i = 0; i < errorEls.length; i++) {
      errorEls[i].textContent = '';
      errorEls[i].style.display = 'none';
    }
    var inputs = form.querySelectorAll('.jtree-form__input--error');
    for (var j = 0; j < inputs.length; j++) {
      inputs[j].classList.remove('jtree-form__input--error');
    }
    formMessage.style.display = 'none';
    formMessage.textContent = '';
    formMessage.className = 'jtree-form__message';
  }

  /**
   * Validate all fields
   */
  function validateAll() {
    var isValid = true;
    for (var fieldName in validators) {
      var el = form.querySelector('[name="' + fieldName + '"]');
      if (!el) continue;
      var value = el.type === 'checkbox' ? '' : el.value;
      var error = validators[fieldName](value, el);
      showFieldError(fieldName, error);
      if (error) isValid = false;
    }
    return isValid;
  }

  /**
   * Set loading state
   */
  function setLoading(loading) {
    submitBtn.disabled = loading;
    btnText.style.display = loading ? 'none' : 'inline';
    btnLoading.style.display = loading ? 'inline' : 'none';
  }

  /**
   * Show form-level message
   */
  function showMessage(text, type) {
    formMessage.textContent = text;
    formMessage.className = 'jtree-form__message jtree-form__message--' + type;
    formMessage.style.display = 'block';
  }

  /**
   * Handle form submission
   */
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    clearErrors();

    // Check honeypot
    var hp = form.querySelector('[name="hp_field"]');
    if (hp && hp.value) return;

    // Validate
    if (!validateAll()) return;

    // Gather data
    var data = {
      parent_first_name: form.parent_first_name.value.trim(),
      parent_last_name: form.parent_last_name.value.trim(),
      parent_email: form.parent_email.value.trim(),
      parent_phone: form.parent_phone.value.trim(),
      teen_age: parseInt(form.teen_age.value, 10),
      program_interest: form.program_interest.value,
      best_time_to_call: form.best_time_to_call.value,
      how_did_you_hear: form.how_did_you_hear.value || null,
      consent_contact: true
    };

    setLoading(true);

    fetch(ENDPOINT, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(data)
    })
      .then(function (response) {
        if (response.ok) {
          // 200 - success
          window.location = THANK_YOU_URL;
          return;
        }

        return response.json().then(function (body) {
          if (response.status >= 400 && response.status < 500) {
            // 4xx - validation errors from server
            if (body.errors && typeof body.errors === 'object') {
              for (var field in body.errors) {
                showFieldError(field, body.errors[field]);
              }
            } else if (body.message) {
              showMessage(body.message, 'error');
            } else {
              showMessage('Please check the form and try again.', 'error');
            }
          } else {
            // 5xx
            showMessage(
              'Something went wrong \u2014 please call us at ' + PHONE_NUMBER,
              'error'
            );
          }
          setLoading(false);
        });
      })
      .catch(function () {
        // Network error
        showMessage(
          'Something went wrong \u2014 please call us at ' + PHONE_NUMBER,
          'error'
        );
        setLoading(false);
      });
  });

  /**
   * Real-time validation on blur
   */
  var validatedFields = Object.keys(validators);
  for (var i = 0; i < validatedFields.length; i++) {
    (function (fieldName) {
      var el = form.querySelector('[name="' + fieldName + '"]');
      if (!el) return;
      var eventType = el.type === 'checkbox' ? 'change' : 'blur';
      el.addEventListener(eventType, function () {
        var value = el.type === 'checkbox' ? '' : el.value;
        var error = validators[fieldName](value, el);
        showFieldError(fieldName, error);
      });
    })(validatedFields[i]);
  }
})();
