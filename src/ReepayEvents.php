<?php

namespace Drupal\commerce_payment_reepay;

/**
 * Defines events for Reepay.
 */
final class ReepayEvents {

  /**
   * The name of the event fired when creating a checkout session.
   *
   * This event allows altering the session data before creating the session.
   *
   * @Event
   */
  const CREATE_CHECKOUT_SESSION = 'commerce_payment_reepay.event.create_checkout_session';

  /**
   * The name of the event fired when processing a payment.
   *
   * @Event
   */
  const PROCESS_PAYMENT = 'commerce_payment_reepay.event.process_payment';

  /**
   * The name of the event fired when processing a webhook
   *
   * @Event
   */
  const PROCESS_WEBHOOK = 'commerce_payment_reepay.event.process_webhook';

}
