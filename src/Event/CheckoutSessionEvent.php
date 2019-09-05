<?php

namespace Drupal\commerce_payment_reepay\Event;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_payment\Entity\PaymentGatewayInterface;

/**
 * The CheckoutSessionEvent class.
 */
class CheckoutSessionEvent extends ReepayEvent {

  /**
   * The session data.
   *
   * @var array
   */
  protected $sessionData;

  /**
   * Constructs a new CheckoutSessionEvent.
   *
   * @param \Drupal\commerce_payment\Entity\PaymentGatewayInterface $paymentGateway
   *   The payment gateway.
   * @param \Drupal\commerce_order\Entity\OrderInterface $order
   *   The order.
   * @param array $sessionData
   *   The session data.
   */
  public function __construct(PaymentGatewayInterface $paymentGateway, OrderInterface $order, array $sessionData) {
    parent::__construct($paymentGateway, $order);
    $this->sessionData = $sessionData;
  }

  /**
   * Get the session data.
   *
   * @return array
   *   The session data.
   */
  public function getSessionData() {
    return $this->sessionData;
  }

  /**
   * Set the session data.
   *
   * @param array $sessionData
   *   The session data.
   *
   * @return self
   *   Return self for chaining.
   */
  public function setSessionData(array $sessionData): self {
    $this->sessionData = $sessionData;

    return $this;
  }

}
