<?php

namespace Drupal\commerce_payment_reepay\Model;

/**
 * Class ReepayCard.
 *
 * @package Drupal\commerce_payment_reepay\Model
 */
class ReepayCard {

  /**
   * The card id.
   *
   * @var string
   */
  public $id = '';

  /**
   * The state of the card.
   *
   * @var string
   */
  public $state = '';

  /**
   * The customer handle.
   *
   * @var string
   */
  public $customer = '';


  /**
   * The card type.
   *
   * @var string
   */
  public $cardType = '';

  /**
   * ReepayCard constructor.
   */
  public function __construct() {
  }

  /**
   * Get the card id.
   *
   * @return string
   *   The card id.
   */
  public function getId(): string {
    return $this->id;
  }

  /**
   * Set the card id.
   *
   * @param string $id
   *   The card id.
   *
   * @return self
   *   Self for chaining.
   */
  public function setId(string $id): self {
    $this->id = $id;
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
   * Get the customer handle.
   *
   * @return string
   *   The customer handle.
   */
  public function getCustomer(): string {
    return $this->customer;
  }

  /**
   * Set the customer handle.
   *
   * @param string $customer
   *   The customer handle.
   *
   * @return self
   *   Self for chaining.
   */
  public function setCustomer(string $customer): self {
    $this->customer = $customer;
    return $this;
  }

  /**
   * Get the card type.
   *
   * @return string
   *   The card type.
   */
  public function getCardType(): string {
    return $this->cardType;
  }

  /**
   * Set the card type.
   *
   * @param string $cardType
   *   The card type.
   *
   * @return self
   *   Self for chaining.
   */
  public function setCardType(string $cardType): self {
    $this->cardType = $cardType;
    return $this;
  }

  /**
   * Get the card type.
   *
   * @return string
   *   The card type.
   */
  public function getCard_Type(): string {
    return $this->getCardType();
  }

  /**
   * Set the card type.
   *
   * @param string $cardType
   *   The card type.
   *
   * @return self
   *   Self for chaining.
   */
  public function setCard_Type(string $cardType): self {
    return $this->setCardType($cardType);
  }

}
