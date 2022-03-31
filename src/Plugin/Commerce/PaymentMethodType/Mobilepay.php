<?php

namespace Drupal\commerce_payment_reepay\Plugin\Commerce\PaymentMethodType;

use \Drupal\entity\BundleFieldDefinition;
use Drupal\commerce_payment\Entity\PaymentMethodInterface;
use Drupal\commerce_payment\Plugin\Commerce\PaymentMethodType\PaymentMethodTypeBase;

/**
 * Provides a Mobilepay payment type.
 *
 * @CommercePaymentMethodType(
 *   id = "mobilepay",
 *   label = @Translation("Mobilepay"),
 *   create_label = @Translation("Mobilepay"),
 * )
 */
class Mobilepay extends PaymentMethodTypeBase {

  /**
   * {@inheritdoc}
   */
  public function buildLabel(PaymentMethodInterface $payment_method) {
    return $this->t('Mobilepay');
  }

}
