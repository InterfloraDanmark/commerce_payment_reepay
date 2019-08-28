<?php

namespace Drupal\commerce_payment_reepay;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_payment_reepay\Event\WebhookEvent;
use Drupal\commerce_payment_reepay\Event\PaymentEvent;
use Drupal\commerce_payment_reepay\Event\CheckoutSessionEvent;
use Drupal\commerce_payment_reepay\Exception\WebhookException;
use Drupal\commerce_payment_reepay\Model\ReepayCharge;
use Drupal\commerce_payment_reepay\ReepayEvents;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

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
    $sessionData = $event->getSessionData();
    // If neither 'order' nor 'invoice' have been populated set defaults before creating the session.
    if (empty($sessionData['order']) && empty($sessionData['invoice'])) {
      $orderNumber = $configuration['order_number_prefix'] . $order->id();
      $totalPrice = $order->getTotalPrice();
      $sessionData['order'] = [
        'handle' => $order->uuid(),
        'amount' => round($totalPrice->getNumber() * 100, 0),
        'currency' => $totalPrice->getCurrencyCode(),
        'ordertext' => $this->t('Order @order_number', ['@order_number' => $orderNumber], ['context' => 'Reepay']),
        'customer' => [
          'email' => $order->mail->value,
        ],
      ];
      $event->setSessionData($sessionData);
    }
  }

  /**
   * Process the payment event.
   *
   * @param \Drupal\commerce_payment_reepay\Event\PaymentEvent $event
   *   The payment event.
   */
  public function onProcessPayment(PaymentEvent $event) {
    // Process the payment and update its state.
    $payment = $event->getPayment();
    $charge = $event->getCharge();
    $paymentGatewayPlugin = $event->getPaymentGateway()->getPlugin();
    $paymentGatewayPlugin->processPayment($payment, $charge);
  }

  /**
   * Process the webhook event.
   *
   * @param \Drupal\commerce_payment_reepay\Event\WebhookEvent $event
   *   The webhook event.
   */
  public function onProcessWebhook(WebhookEvent $event) {
    $paymentGatewayPlugin = $event->getPaymentGateway()->getPlugin();
    // Default is to only process invoice_authorized-events.
    if ($event->getEventType() == 'invoice_authorized') {
      $invoiceHandle = $event->getInvoiceHandle() ?? '';
      $order = $paymentGatewayPlugin->loadOrderByProperties(['uuid' => $invoiceHandle]);
      $charge = $paymentGatewayPlugin->getReepayApi()->getCharge($invoiceHandle);
      if (!$order instanceof OrderInterface) {
        throw new WebhookException('Order not found: ' . $invoiceHandle);
      }
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

}
