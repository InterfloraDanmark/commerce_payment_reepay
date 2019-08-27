<?php

namespace Drupal\commerce_payment_reepay\Exception;

use Drupal\commerce_payment\Exception\PaymentGatewayException;

/**
 * Thrown when a webhook fails.
 */
class WebhookException extends PaymentGatewayException {}
