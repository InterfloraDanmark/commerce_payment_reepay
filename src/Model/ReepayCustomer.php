<?php

namespace Drupal\commerce_payment_reepay\Model;

/**
 * Class ReepayCustomer.
 *
 * @package Drupal\commerce_payment_reepay\Model
 */
class ReepayCustomer {

  /**
   * The customer handle.
   *
   * @var string
   */
  public $handle = '';

  /**
   * The email.
   *
   * @var string
   */
  public $email = '';

  /**
   * The first name.
   *
   * @var string
   */
  public $firstName = '';

  /**
   * The last name.
   *
   * @var string
   */
  public $lastName = '';

  /**
   * The company.
   *
   * @var string
   */
  public $company = '';

  /**
   * ReepayCustomer constructor.
   */
  public function __construct() {
  }

  /**
   * Get the customer handle.
   *
   * @return string
   *   The customer handle.
   */
  public function getHandle(): string {
    return $this->handle;
  }

  /**
   * Set the customer handle.
   *
   * @param string $handle
   *   The customer handle.
   *
   * @return self
   *   Self for chaining.
   */
  public function setHandle(string $handle): self {
    $this->handle = $handle;
    return $this;
  }

  /**
   * Get the email.
   *
   * @return string
   *   The email.
   */
  public function getEmail(): string {
    return $this->email;
  }

  /**
   * Set the email.
   *
   * @param string $email
   *   The email.
   *
   * @return self
   *   Self for chaining.
   */
  public function setEmail(string $email): self {
    $this->email = $email;
    return $this;
  }

  /**
   * Get the first name.
   *
   * @return string
   *   The first name.
   */
  public function getFirstName(): string {
    return $this->firstName;
  }

  /**
   * Set the first name.
   *
   * @param string $firstName
   *   The first name.
   *
   * @return self
   *   Self for chaining.
   */
  public function setFirstName(string $firstName): self {
    $this->firstName = $firstName;
    return $this;
  }

  /**
   * Get the last name.
   *
   * @return string
   *   The last name.
   */
  public function getLastName(): string {
    return $this->lastName;
  }

  /**
   * Set the last name.
   *
   * @param string $lastName
   *   The last name.
   *
   * @return self
   *   Self for chaining.
   */
  public function setLastName(string $lastName): self {
    $this->lastName = $lastName;
    return $this;
  }

  /**
   * Get the full name.
   *
   * @return string
   *   The full name.
   */
  public function getFullName(): string {
    return $this->getFirstName() . ' ' . $this->getLastName();
  }

  /**
   * Get the company.
   *
   * @return string
   *   The company.
   */
  public function getCompany(): string {
    return $this->company;
  }

  /**
   * Set the company.
   *
   * @param string $company
   *   The company.
   *
   * @return self
   *   Self for chaining.
   */
  public function setCompany(string $company): self {
    $this->company = $company;
    return $this;
  }

}
