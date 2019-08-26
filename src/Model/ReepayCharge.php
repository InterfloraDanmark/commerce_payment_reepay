<?php

/**
 * @file
 */

namespace Drupal\commerce_payment_reepay\Model;

use \Drupal\commerce_price\Price;

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
   * ReepayCharge constructor.
   */
  public function __construct() {
  }

  /**
   * Get the charge handle.
   *
   * @return string
   */
  public function getHandle(): string {
    return $this->handle;
  }

  /**
   * Set the charge handle.
   *
   * @param string $handle
   *
   * @return \Drupal\commerce_payment_reepay\Model\ReepayCharge
   */
  public function setHandle(string $handle): self {
    $this->handle = $handle;

    return $this;
  }

  /**
   * Get the state.
   *
   * @return string
   */
  public function getState(): string {
    return $this->state;
  }

  /**
   * Set the state.
   *
   * @param string $state
   *
   * @return \Drupal\commerce_payment_reepay\Model\ReepayCharge
   */
  public function setState(string $state): self {
    $this->state = $state;

    return $this;
  }

  /**
   * Get the amount.
   *
   * @return int
   */
  public function getAmount(): int {
    return $this->amount;
  }

  /**
   * Set the amount.
   *
   * @param int $amount
   *
   * @return \Drupal\commerce_payment_reepay\Model\ReepayCharge
   */
  public function setAmount(int $amount): self {
    $this->amount = $amount;

    return $this;
  }

  /**
   * Get the currency.
   *
   * @return string
   */
  public function getCurrency(): string {
    return $this->currency;
  }

  /**
   * Set the currency.
   *
   * @param string $currency
   *
   * @return \Drupal\commerce_payment_reepay\Model\ReepayCharge
   */
  public function setCurrency(string $currency): self {
    $this->currency = $currency;

    return $this;
  }

  /**
   * Get the price.
   *
   * @return \Drupal\commerce_price\Price
   */
  public function getPrice(): Price {
    return new Price($this->amount / 100, $this->currency);
  }

  /**
   * Set the price.
   *
   * @param \Drupal\commerce_price\Price $price
   *
   * @return \Drupal\commerce_payment_reepay\Model\ReepayCharge
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
   */
  public function getTransaction(): string {
    return $this->transaction;
  }

  /**
   * Set the transaction.
   *
   * @param string $transaction
   *
   * @return \Drupal\commerce_payment_reepay\Model\ReepayCharge
   */
  public function setTransaction(string $transaction): self {
    $this->transaction = $transaction;

    return $this;
  }

  /**
   * Get the charge source.
   */
  public function getSource(): object {
    return $this->source;
  }

  /**
   * Set the charge source.
   *
   * @param object $source
   *
   * @return \Drupal\commerce_payment_reepay\Model\ReepayCharge
   */
  public function setSource(object $source): self {
    $this->source = $source;

    return $this;
  }

}
