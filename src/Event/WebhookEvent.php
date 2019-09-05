<?php

namespace Drupal\commerce_payment_reepay\Event;

use Drupal\commerce_payment\Entity\PaymentGatewayInterface;

/**
 * The webhook event.
 *
 * @see \Drupal\commerce_payment_reepay\ReepayEvents
 */
class WebhookEvent extends ReepayEvent {

  /**
   * The webhook contents.
   *
   * @var object
   */
  protected $contents;

  /**
   * Constructs a new WebhookEvent.
   *
   * @param \Drupal\commerce_payment\Entity\PaymentGatewayInterface $paymentGateway
   *   The payment gateway.
   * @param \object $contents
   *   The webhook contents.
   */
  public function __construct(PaymentGatewayInterface $paymentGateway, object $contents) {
    parent::__construct($paymentGateway);
    $this->contents = $contents;
  }

  /**
   * Get the webhook contents.
   *
   * @return object
   *   The contents.
   */
  public function getContents(): object {
    return $this->contents;
  }

  /**
   * The unique id for the webhook.
   *
   * @return string
   *   The unique id.
   */
  public function getId() {
    return $this->contents->id;
  }

  /**
   * The id of the event triggering the webhook.
   *
   * @return string
   *   The event id.
   */
  public function getEventId() {
    return $this->contents->event_id;
  }

  /**
   * The event type.
   *
   * @return string
   *   The event type
   */
  public function getEventType() {
    return $this->contents->event_type;
  }

  /**
   * Timestamp in ISO-8601 when the webhook was triggered.
   *
   * @return string
   *   The timestamp.
   */
  public function getTimestamp() {
    return $this->contents->timestamp;
  }

  /**
   * Signature to verify the authenticity of the webhook.
   *
   * @return string
   *   The signature.
   */
  public function getSignature() {
    return $this->contents->signature;
  }

  /**
   * Optional customer handle.
   *
   * @return string|null
   *   The customer handle.
   */
  public function getCustomerHandle() {
    return $this->contents->customer ?? NULL;
  }

  /**
   * Optional payment method id.
   *
   * @return string|null
   *   The payment method.
   */
  public function getPaymentMethod() {
    return $this->contents->payment_method ?? NULL;
  }

  /**
   * The optional ubscription handle.
   *
   * Only included if event relates to a subscription resource.
   *
   * @return string|null
   *   The subscription handle.
   */
  public function getSubscriptionHandle() {
    return $this->contents->subscription ?? NULL;
  }

  /**
   * The invoice handle.
   *
   * Only included if event relates to an invoice resource.
   *
   * @return string|null
   *   The invoice handle.
   */
  public function getInvoiceHandle() {
    return $this->contents->invoice ?? NULL;
  }

  /**
   * The optional transaction id.
   *
   * For invoice events a transaction id is included if the event was result
   * of a transaction, e.g. a card settle transaction. The transaction id the
   * same as refund id for refunds.
   *
   * @return string|null
   *   The transaction id.
   */
  public function getTransactionId() {
    return $this->contents->transaction ?? NULL;
  }

  /**
   * The optional credit note.
   *
   * Only included if the event relates to an invoice refund or an invoice
   * credit.
   *
   * @return string|null
   *   The credit note.
   */
  public function getCreditNote() {
    return $this->contents->credit_note ?? NULL;
  }

  /**
   * The optional credit id.
   *
   * Only included if the event is an invoice credit.
   *
   * @return string|null
   *   The credit id.
   */
  public function getCreditId() {
    return $this->contents->credit ?? NULL;
  }

  /**
   * Check the webhook signature.
   *
   * @return bool
   *   TRUE if the signature matches otherwise FALSE.
   */
  public function validSignature() {
    $configuration = $this->getPaymentGateway()->getPlugin()->getConfiguration();
    $message = $this->contents->timestamp . $this->contents->id;
    $calculatedSignature = hash_hmac('sha256', $message, $configuration['webhook_key']);
    return $calculatedSignature == $this->contents->signature;
  }

}
