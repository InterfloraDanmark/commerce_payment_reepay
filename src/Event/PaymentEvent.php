<?php

namespace Drupal\commerce_payment_reepay\Event;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_payment\Entity\PaymentGatewayInterface;
use Drupal\commerce_payment_reepay\Model\ReepayCharge;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

/**
 * The PaymentEvent class.
 */
class PaymentEvent extends Event {

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
   * The request.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   * The reepay charge.
   *
   * @var \Drupal\commerce_payment_reepay\Model\ReepayCharge
   */
  protected $charge;

  /**
   * Constructs a new PaymentEvent.
   *
   * @param \Drupal\commerce_payment\Entity\PaymentGatewayInterface $paymentGateway
   *   The payment gateway.
   * @param \Drupal\commerce_order\Entity\OrderInterface $order
   *   The order.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   */
  public function __construct(PaymentGatewayInterface $paymentGateway, OrderInterface $order, Request $request) {
    $this->paymentGateway = $paymentGateway;
    $this->order = $order;
    $this->request = $request;
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
  public function getOrder(): OrderInterface {
    return $this->order;
  }

  /**
   * Get the request.
   *
   * @return \Symfony\Component\HttpFoundation\Request
   *   The request.
   */
  public function getRequest(): Request {
    return $this->request;
  }

  /**
   * Get the Reepay charge.
   *
   * @return \Drupal\commerce_payment_reepay\Model\ReepayCharge|null
   *   The Reepay charge or NULL.
   */
  public function getCharge() {
    return $this->charge;
  }

  /**
   * Set the Reepay charge.
   *
   * @param \Drupal\commerce_payment_reepay\Model\ReepayCharge $charge
   *   The Reepay charge.
   *
   * @return self
   *   Return self for chaining.
   */
  public function setCharge(ReepayCharge $charge): self {
    $this->charge = $charge;
    return $this;
  }

  /**
   * Get the session id.
   *
   * @return string|null
   *   The session id.
   */
  public function getSessionId() {
    return $this->request->query->get('id') ?? NULL;
  }

  /**
   * Get the invoice handle.
   *
   * @return string|null
   *   The invoice handle.
   */
  public function getInvoiceHandle() {
    return $this->request->query->get('invoice') ?? NULL;
  }

  /**
   * Get the customer handle.
   *
   * @return string|null
   *   The customer handle.
   */
  public function getCustomerHandle() {
    return $this->request->query->get('customer') ?? NULL;
  }

  /**
   * Get the subscription handle.
   *
   * @return string|null
   *   The subscription handle.
   */
  public function getSubscriptionHandle() {
    return $this->request->query->get('subscription') ?? NULL;
  }

  /**
   * Get the payment method.
   *
   * @return string|null
   *   The payment method.
   */
  public function getPaymentMethod() {
    return $this->request->query->get('payment_method') ?? NULL;
  }

  /**
   * Get the error.
   *
   * @return string|null
   *   The error.
   */
  public function getError() {
    return $this->request->query->get('error') ?? NULL;
  }

}
