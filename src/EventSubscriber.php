<?php

namespace Drupal\commerce_payment_reepay;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_payment_reepay\Event\WebhookEvent;
use Drupal\commerce_payment_reepay\Event\PaymentEvent;
use Drupal\commerce_payment_reepay\Event\CheckoutSessionEvent;
use Drupal\commerce_payment_reepay\Exception\WebhookException;
use Drupal\commerce_payment_reepay\Model\ReepayCharge;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * The EventSubscriber-class with default eventhandlers.
 */
class EventSubscriber implements EventSubscriberInterface {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      ReepayEvents::CREATE_CHECKOUT_SESSION => 'onCreateCheckoutSession',
      ReepayEvents::PROCESS_PAYMENT => 'onProcessPayment',
      ReepayEvents::PROCESS_WEBHOOK => 'onProcessWebhook',
      ReepayEvents::LOOKUP_ORDER => 'onLookupOrder',
    ];
  }

  /**
   * Process the create checkout session event.
   *
   * @param \Drupal\commerce_payment_reepay\Event\CheckoutSessionEvent $event
   *   The checkout session event.
   */
  public function onCreateCheckoutSession(CheckoutSessionEvent $event) {
    $order = $event->getOrder();
    $configuration = $event->getPaymentGateway()->getPluginConfiguration();
    $sessionType = $configuration['session_type'];
    $sessionData = $event->getSessionData();
    // If this is a charge session and neither 'order' nor 'invoice' have been
    // populated set defaults before creating the session.
    if ($sessionType == 'charge' && empty($sessionData['order']) && empty($sessionData['invoice'])) {
      $orderHandle = $configuration['order_handle_prefix'] . $order->id();
      $totalPrice = $order->getTotalPrice();
      $sessionData['order'] = [
        'handle' => $orderHandle,
        'amount' => round($totalPrice->getNumber() * 100, 0),
        'currency' => $totalPrice->getCurrencyCode(),
        'ordertext' => $this->t('Order @handle', ['@handle' => $orderHandle], ['context' => 'Reepay']),
        'customer' => [
          'email' => $order->mail->value,
        ],
      ];
      $event->setSessionData($sessionData);
    }
    // Recurring sessions need a custom implementation.
    elseif ($sessionType == 'recurring') {
      // Do nothing.
    }
  }

  /**
   * Process the payment event.
   *
   * @param \Drupal\commerce_payment_reepay\Event\PaymentEvent $event
   *   The payment event.
   */
  public function onProcessPayment(PaymentEvent $event) {
    $charge = $event->getCharge();
    if ($charge instanceof ReepayCharge) {
      $paymentGatewayPlugin = $event->getPaymentGateway()->getPlugin();
      $payment = $paymentGatewayPlugin->getPayment($event->getOrder(), $charge);
      $paymentGatewayPlugin->processPayment($payment, $charge);
    }
  }

  /**
   * Process the webhook event.
   *
   * @param \Drupal\commerce_payment_reepay\Event\WebhookEvent $event
   *   The webhook event.
   */
  public function onProcessWebhook(WebhookEvent $event) {
    $handled_events = [
      'invoice_authorized',
      'invoice_settled',
    ];
    if (in_array($event->getEventType(), $handled_events)) {
      $paymentGatewayPlugin = $event->getPaymentGateway()->getPlugin();
      $invoiceHandle = $event->getInvoiceHandle() ?? '';
      $order = $event->getOrder();
      $charge = $paymentGatewayPlugin->getReepayApi()->getCharge($invoiceHandle);
      if (!$charge instanceof ReepayCharge) {
        throw new WebhookException('Charge not found: ' . $invoiceHandle);
      }
      $payment = $paymentGatewayPlugin->getPayment($order, $charge);
      $paymentGatewayPlugin->processPayment($payment, $charge);
    }
    else {
      throw new WebhookException('Unhandled event type: ' . $event->getEventType());
    }
  }

  /**
   * Process the look up order event.
   *
   * @param \Drupal\commerce_payment_reepay\Event\WebhookEvent $event
   *   The webhook event.
   */
  public function onLookupOrder(WebhookEvent $event) {
    if (!$event->getOrder() instanceof OrderInterface) {
      $invoiceHandle = $event->getInvoiceHandle() ?? '';
      $paymentGatewayPlugin = $event->getPaymentGateway()->getPlugin();
      $configuration = $paymentGatewayPlugin->getConfiguration();
      $orderNumberPrefix = $configuration['order_handle_prefix'];
      if (strpos($invoiceHandle, $orderNumberPrefix) === 0) {
        $orderId = substr($invoiceHandle, strlen($orderNumberPrefix));
        $order = $paymentGatewayPlugin->loadOrderByProperties(['order_id' => $orderId]);
        $event->setOrder($order);
      }
    }
  }

}
