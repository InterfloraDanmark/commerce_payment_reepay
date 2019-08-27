/**
 * @file
 * Attaches the behaviors for the reepay checkout form.
 */
(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.commercePaymentReepayCheckout = {
    attach: function attach(context) {
      var $context = $(context);

      var reepayCheckout;
      var checkoutType = drupalSettings.commercePaymentReepayCheckout.checkoutType;
      var sessionID = drupalSettings.commercePaymentReepayCheckout.sessionID;

      switch (checkoutType) {
        case 'window':
          rp = new Reepay.WindowCheckout(sessionID);
          break;
        default:
          console.error('Invalid checkout type');
          break;
      }

    }
  };
})(jQuery, Drupal, drupalSettings);
