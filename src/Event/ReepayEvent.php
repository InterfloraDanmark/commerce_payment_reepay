<?php

namespace Drupal\commerce_payment_reepay\Event;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_payment\Entity\PaymentGatewayInterface;
use Drupal\commerce_payment_reepay\ReepayApi;
use Symfony\Component\EventDispatcher\Event;

/**
 * The ReepayEvent class.
 */
abstract class ReepayEvent extends Event {

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
   * The Reepay API.
   *
   * @var Drupal\commerce_payment_reepay\ReepayAPI
   */
  protected $reepayApi;

  /**
   * Constructs a new ReepayEvent.
   *
   * @param \Drupal\commerce_payment\Entity\PaymentGatewayInterface $paymentGateway
   *   The payment gateway.
   * @param \Drupal\commerce_order\Entity\OrderInterface|null $order
   *   The order.
   */
  public function __construct(PaymentGatewayInterface $paymentGateway, ?OrderInterface $order = NULL) {
    $this->paymentGateway = $paymentGateway;
    $this->order = $order;
    $configuration = $paymentGateway->getPluginConfiguration();
    $this->reepayApi = new ReepayApi($configuration['private_key']);
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
   * @return \Drupal\commerce_order\Entity\OrderInterface|null
   *   The order.
   */
  public function getOrder(): ?OrderInterface {
    return $this->order;
  }

  /**
   * Set the order.
   *
   * @param \Drupal\commerce_order\Entity\OrderInterface|null $order
   *   The order.
   *
   * @return self
   *   Return self for chaining.
   */
  public function setOrder(?OrderInterface $order): self {
    $this->order = $order;
    return $this;
  }

  /**
   * Get the Reepay API object.
   *
   * @return \Drupal\commerce_payment_reepay\ReepayAPI
   *   The Reepay API object.
   */
  public function getReepayApi() {
    return $this->reepayApi;
  }

}
