<?php

namespace Drupal\commerce_payment_reepay;

use Drupal\commerce_order\Entity\Order;
use Drupal\Core\Site\Settings;
use GuzzleHttp\psr7\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class ReepayCheckoutApi
 *
 * @package Drupal\commerce_payment_reepay
 */
class ReepayCheckoutApi {

  /**
   * Base URL for the Reepay Checkout API.
   *
   * @var string
   */
  protected $baseUrl = 'https://checkout-api.reepay.com/v1/';

  /**
   * The Guzzle HTTP client.
   *
   * @var \GuzzleHttp\Client;
   */
  protected $httpClient;

  /**
   * The serializer.
   *
   * @var \Symfony\Component\Serializer\Serializer
   */
  protected $serializer;

  /**
   * ReepayCheckoutApi constructor.
   *
   * @param string $privateKey
   *   The Reepay private API key.
   */
  public function __construct($privateKey = NULL) {
    if (!empty($privateKey)) {
      $this->httpClient = new Client([
        'base_uri' => $this->baseUrl,
        'auth' => [$privateKey, ''],
      ]);
    }
    $encoders = ['json' => new JsonDecode()];
    $normalizers = [new GetSetMethodNormalizer()];
    $this->serializer = new Serializer($normalizers, $encoders);
  }

  /**
   * Get the headers for a request.
   *
   * @return array
   *   An array of HTTP headers.
   */
  protected function getHeaders() {
    return [
      'Content-Type' => 'application/json',
      'Accept' => 'application/json',
    ];
  }

  /**
   * Perform a POST request.
   *
   * @param string $path
   *   The path of the resource.
   * @param mixed $data
   *   The data to send.
   * @param string $className
   *   Name of the class to deserialize to.
   *
   * @return mixed
   *   The response.
   */
  protected function postRequest(string $path, $data = '', string $className = '') {
    try {
      $response = $this->httpClient->post($path, [
        'json' => $data,
        'headers' => $this->getHeaders(),
      ]);

      $responseBody = $this->handleResponse($response->getBody()->getContents(), $className);
    }
    catch (RequestException $exception) {
      $responseBody = [
        'code' => $exception->getCode(),
        'message' => $this->handleException($exception),
      ];
    }
    return $responseBody;
  }

  /**
   * Perform a DELETE request.
   *
   * @param string $path
   *   The path of the resource.
   * @param mixed $data
   *   The data to send.
   * @param string $className
   *   Name of the class to deserialize to.
   *
   * @return mixed
   *   The server response.
   */
  protected function deleteRequest(string $path, $data = '', string $className = '') {
    try {
      $response = $this->httpClient->delete($path, [
        'json' => $data,
        'headers' => $this->getHeaders(),
      ]);
      $responseBody = $this->handleResponse($response->getBody()->getContents(), $className);
    }
    catch (RequestException $exception) {
      $responseBody = [
        'code' => $exception->getCode(),
        'message' => $this->handleException($exception),
      ];
    }
    return $responseBody;
  }

  /**
   * Deserialize response.
   *
   * @param string $body
   *   The content to deserialize.
   * @param string $className
   *   The content class name.
   *
   * @return object
   *   A class or a json object.
   */
  protected function handleResponse(string $body, string $className = '') {
    $classNamespaceName = 'Drupal\\commerce_payment_reepay\\Model\\' . $className;
    if (!empty($className) && class_exists($classNamespaceName)) {
      return $this->serializer->deserialize($body, $classNamespaceName, 'json');
    }
    return json_decode($body);
  }

  /**
   * Deserialize exception response,
   *
   * @param \GuzzleHttp\Exception\RequestException $exception
   *
   * @return mixed
   */
  protected function handleException(RequestException $exception) {
    $response = $exception->getResponse();
    if ($response instanceof Request) {
      return json_decode(
        $response->getBody()->getContents()
      );
    }
    else {
      return $exception->getMessage();
    }
  }

  /**
   * Create a charge checkout session.
   *
   * @param mixed $data
   *   The session data.
   *
   * @return mixed
   *   The response object or FALSE.
   */
  public function createChargeSession($data) {
    return $this->postRequest('session/charge', $data, 'ReepayCheckoutSession');
  }

  /**
   * Create a new customer.
   *
   * @param mixed $data
   *   The session data.
   *
   * @return mixed
   *   The response object or FALSE.
   */
  public function createRecurringSession($data) {
    return $this->postRequest('session/recurring', $data, 'ReepayCheckoutSession');
  }

  /**
   * Delete a checkout session.
   *
   * @param string $sessionId
   *   The session ID.
   *
   * @return mixed
   *   The response object or FALSE.
   */
  public function deleteSession(string $sessionId) {
    return $this->deleteRequest('session/' . $sessionId);
  }

}
