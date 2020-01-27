<?php

namespace Drupal\commerce_payment_reepay\Model;

use Drupal\commerce_price\Price;

/**
 * Class ReepayCharge.
 *
 * @package Drupal\commerce_payment_reepay\Model
 */
class ReepayCharge {

  /**
   * The charge handle.
   *
   * @var string
   */
  public $handle = '';

  /**
   * The state of the charge.
   *
   * @var string
   */
  public $state = '';

  /**
   * The amount of the charge.
   *
   * @var int
   */
  public $amount = '';

  /**
   * The currency of the charge.
   *
   * @var string
   */
  public $currency = '';

  /**
   * The transaction.
   *
   * @var string
   */
  public $transaction = '';

  /**
   * The charge source.
   *
   * @var object
   */
  public $source = NULL;

  /**
   * Recurring payment method.
   *
   * @var string
   */
  public $recurring_payment_method = '';

  /**
   * ReepayCharge constructor.
   */
  public function __construct() {
  }

  /**
   * Get the charge handle.
   *
   * @return string
   *   The charge handle.
   */
  public function getHandle(): string {
    return $this->handle;
  }

  /**
   * Set the charge handle.
   *
   * @param string $handle
   *   The charge handle.
   *
   * @return self
   *   Self for chaining.
   */
  public function setHandle(string $handle): self {
    $this->handle = $handle;

    return $this;
  }

  /**
   * Get the state.
   *
   * @return string
   *   The state.
   */
  public function getState(): string {
    return $this->state;
  }

  /**
   * Set the state.
   *
   * @param string $state
   *   The state.
   *
   * @return self
   *   Self for chaining.
   */
  public function setState(string $state): self {
    $this->state = $state;

    return $this;
  }

  /**
   * Get the amount.
   *
   * @return int
   *   The amount.
   */
  public function getAmount(): int {
    return $this->amount;
  }

  /**
   * Set the amount.
   *
   * @param int $amount
   *   The amount.
   *
   * @return self
   *   Self for chaining.
   */
  public function setAmount(int $amount): self {
    $this->amount = $amount;

    return $this;
  }

  /**
   * Get the currency.
   *
   * @return string
   *   The currency.
   */
  public function getCurrency(): string {
    return $this->currency;
  }

  /**
   * Set the currency.
   *
   * @param string $currency
   *   The currency.
   *
   * @return self
   *   Self for chaining.
   */
  public function setCurrency(string $currency): self {
    $this->currency = $currency;

    return $this;
  }

  /**
   * Get the price.
   *
   * @return \Drupal\commerce_price\Price
   *   The price.
   */
  public function getPrice(): Price {
    return new Price($this->amount / 100, $this->currency);
  }

  /**
   * Set the price.
   *
   * @param \Drupal\commerce_price\Price $price
   *   The price.
   *
   * @return self
   *   Self for chaining.
   */
  public function setPrice(Price $price): self {
    $this->amount = (int) ($price->getNumber() * 100);
    $this->currency = $price->getCurrencyCode();

    return $this;
  }

  /**
   * Get the transaction.
   *
   * @return string
   *   The transaction.
   */
  public function getTransaction(): string {
    return $this->transaction;
  }

  /**
   * Set the transaction.
   *
   * @param string $transaction
   *   The transaction.
   *
   * @return self
   *   Self for chaining.
   */
  public function setTransaction(string $transaction): self {
    $this->transaction = $transaction;

    return $this;
  }

  /**
   * Get the charge source.
   *
   * @return object
   *   The charge source.
   */
  public function getSource(): ?object {
    return $this->source;
  }

  /**
   * Set the charge source.
   *
   * @param object $source
   *   The charge source.
   *
   * @return self
   *   Self for chaining.
   */
  public function setSource($source): self {
    $this->source = $source;

    return $this;
  }

  /**
   * Get recurring payment method.
   *
   * @return string
   *   The recurring payment method.
   */
  public function getRecurring_payment_method(): string {
    return $this->recurring_payment_method;
  }

  /**
   * Set recurring payment method.
   *
   * @param string $recurringPaymentMethod
   *    The recurring payment method.
   *
   * @return self
   *   Self for chaining.
   */
  public function setRecurring_payment_method(string $recurringPaymentMethod): self {
    $this->recurring_payment_method = $recurringPaymentMethod;

    return $this;
  }

}
