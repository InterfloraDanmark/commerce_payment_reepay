<?php

namespace Drupal\commerce_payment_reepay\Plugin\QueueWorker;

use Drupal\commerce_payment_reepay\Exception\WebhookException;
use Drupal\commerce_payment_reepay\Model\ReepayCharge;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\interflora_order\Entity\Order;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation od the if_payment_callback_queue queueworker.
 *
 * @QueueWorker (
 *   id = "reepay_payment_callback_queue",
 *   title = @Translation("Handle webhook callbacks from Reepay"),
 *   cron = {"time" = 60}
 * )
 */
class PaymentCallbackQueue extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  /**
   * The payment storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  private $payment_gateway_storage;

  /**
   * PaymentCallbackQueue constructor.
   *
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   * @param $payment_gateway_storage
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, $entityTypeManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->payment_gateway_storage = $entityTypeManager->getStorage('commerce_payment_gateway');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    $paymentGatewayPluginId = $data->paymentPluginId;
    $invoiceHandle = $data->invoiceHandle ?? '';
    $orderId = $data->id;
    $order = Order::load($orderId);
    $paymentGateway = $this->payment_gateway_storage->load($paymentGatewayPluginId);
    $paymentGatewayPlugin = $paymentGateway->getPlugin();
    IF (!$paymentGatewayPlugin) {
      $payments = $order->getPayments();
      /** @var \Drupal\commerce_payment\Entity\Payment $payment */
      $payment = reset($payments);
      $paymentGatewayPlugin = $payment->getPaymentGateway()->getPlugin();
    }
    $charge = $paymentGatewayPlugin->getReepayApi()->getCharge($invoiceHandle);
    if (!$charge instanceof ReepayCharge) {
      throw new WebhookException('Charge not found: ' . $invoiceHandle);
    }
    if ($order->getState()->value === 'draft') {
      $disableCallbackTransition = \Drupal::state()
        ->get('reepay.diable_callback_transition', FALSE);
      if (!$disableCallbackTransition) {
        \Drupal::logger('reepay')
          ->notice('Apply placed transition: ' . $order->id());
        $payments = $order->getPayments();
        if (empty($payments)) {
          $payment = $paymentGatewayPlugin->getPayment($order, $charge);
          $paymentGatewayPlugin->processPayment($payment, $charge);
        }

        $order->getState()->applyTransitionById('place');
        $order->unlock();
        $order->set('checkout_step', 'complete');
        $order->save();
      }
    }
  }

}
