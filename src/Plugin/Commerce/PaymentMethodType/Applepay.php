<?php

namespace Drupal\commerce_payment_reepay\Plugin\Commerce\PaymentMethodType;

use Drupal\commerce_payment\Entity\PaymentMethodInterface;
use Drupal\commerce_payment\Plugin\Commerce\PaymentMethodType\PaymentMethodTypeBase;

/**
 * Provides a Applepay payment type.
 *
 * @CommercePaymentMethodType(
 *   id = "applepay",
 *   label = @Translation("Applepay"),
 *   create_label = @Translation("Applepay"),
 * )
 */
class Applepay extends PaymentMethodTypeBase {

  /**
   * {@inheritdoc}
   */
  public function buildLabel(PaymentMethodInterface $payment_method) {
    return $this->t('Applepay');
  }

}
