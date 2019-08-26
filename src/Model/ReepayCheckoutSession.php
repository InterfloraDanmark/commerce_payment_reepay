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
   * ReepayCheckoutSession constructor.
   */
  public function __construct() {
  }

  /**
   * Get the session ID.
   *
   * @return string
   */
  public function getId(): string {
    return $this->id;
  }

  /**
   * Set the session ID.
   *
   * @param string $id
   *
   * @return \Drupal\commerce_payment_reepay\Model\ReepayCheckoutSession
   */
  public function setId(string $id): self {
    $this->id = $id;

    return $this;
  }

  /**
   * Get the payment url.
   *
   * @return string
   */
  public function getUrl(): string {
    return $this->url;
  }

  /**
   * Set the payment url.
   *
   * @param string $plan
   *
   * @return \Drupal\commerce_payment_reepay\Model\ReepayCheckoutSession
   */
  public function setUrl(string $url): self {
    $this->url = $url;

    return $this;
  }

}
