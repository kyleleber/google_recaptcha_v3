(function ($, Drupal, drupalSettings) {

  'use strict';

  /**
   * Attach handlers to form
   *
   * @type {Drupal~behavior}
   */
  var initialized = false;

  Drupal.behaviors.googleRecaptcha = {
    attach: function (context) {
      let account_id = drupalSettings.recaptcha_sitekey;

      if (!initialized && account_id) {
        recaptchaInit(account_id);
        initialized = true;
      }
    }

  };

  /**
   * Initializes recaptcha v3 based on drupalSettings.
   */
  function recaptchaInit(account_id) {
    grecaptcha.ready(function () {
      // If there are any specified actions, iterate through them.
      if (drupalSettings.actions) {
        drupalSettings.actions.forEach(function (action) {

          grecaptcha.execute(account_id, {action: action.name}).then(function (token) {

            // if the action specified by drupalSettings has the flag of
            // datalayer => true, then we need to push the event/score to
            // the datalayer.
            if (action.datalayer) {
              $.post('/recaptcha_verification/' + token, function (response) {

                const dataLayer = window.dataLayer || [];
                dataLayer.push({
                  'event': 'googleRecaptcha',
                  'eventAction': action.name,
                  'eventScore': response.score,
                });
              });
            }
          });

        });
      }
      else {
        // This is the fallback case to fire on every page if there are no
        // actions specified.
        grecaptcha.execute(account_id);

      }
    });
  }
})(jQuery, Drupal, drupalSettings);
