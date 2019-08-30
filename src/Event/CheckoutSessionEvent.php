<?php

namespace Drupal\commerce_payment_reepay\Event;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_payment\Entity\PaymentGatewayInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * The CheckoutSessionEvent class.
 */
class CheckoutSessionEvent extends Event {

  /**
   * The payment gateway.
   *
   * @var \Drupal\commerce_payment\Entity\PaymentGatewayInterface
   */
  protected $paymentGateway;

  /**
   * The order.
   *
   * @var \Drupal\commerce_order\Entity\OrderInterface
   */
  protected $order;

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
    $this->paymentGateway = $paymentGateway;
    $this->order = $order;
    $this->sessionData = $sessionData;
  }

  /**
   * Get the payment gateway.
   *
   * @return \Drupal\commerce_payment\Entity\PaymentGatewayInterface
   *   The payment gateway.
   */
  public function getPaymentGateway(): PaymentGatewayInterface {
    return $this->paymentGateway;
  }

  /**
   * Get the order.
   *
   * @return \Drupal\commerce_order\Entity\OrderInterface
   *   The order.
   */
  public function getOrder() {
    return $this->order;
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
