<?php

namespace Drupal\commerce_payment_reepay\Model;

/**
 * Class ReepayCheckoutSession.
 *
 * @package Drupal\commerce_payment_reepay\Model
 */
class ReepayCheckoutSession {

  /**
   * The session ID.
   *
   * @var string
   */
  public $id = '';

  /**
   * The url of the payment.
   *
   * @var string
   */
  public $url = '';

  /**
   * Get the session ID.
   *
   * @return string
   *   The session ID.
   */
  public function getId(): string {
    return $this->id;
  }

  /**
   * Set the session ID.
   *
   * @param string $id
   *   The session ID.
   *
   * @return self
   *   Self for chaining.
   */
  public function setId(string $id): self {
    $this->id = $id;
    return $this;
  }

  /**
   * Get the payment url.
   *
   * @return string
   *   The payment url.
   */
  public function getUrl(): string {
    return $this->url;
  }

  /**
   * Set the payment url.
   *
   * @param string $url
   *   The payment url.
   *
   * @return self
   *   Self for chaining.
   */
  public function setUrl(string $url): self {
    $this->url = $url;
    return $this;
  }

}
