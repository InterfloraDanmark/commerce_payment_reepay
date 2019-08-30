<?php

namespace Drupal\commerce_payment_reepay\PluginForm\OffsiteRedirect;

use Drupal\commerce_payment\PluginForm\PaymentOffsiteForm;
use Drupal\commerce_payment_reepay\Event\CheckoutSessionEvent;
use Drupal\commerce_payment_reepay\Exception\WebhookException;
use Drupal\commerce_payment_reepay\Model\ReepayCheckoutSession;
use Drupal\commerce_payment_reepay\ReepayCheckoutApi;
use Drupal\commerce_payment_reepay\ReepayEvents;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Logger\LoggerChannelTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * The ReepayCheckoutForm.
 */
class ReepayCheckoutForm extends PaymentOffsiteForm implements ContainerInjectionInterface {

  use LoggerChannelTrait;
  use StringTranslationTrait;

  /**
   * The event dispatcher.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * Constructs a new ReepayCheckoutForm.
   *
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
   *   The event dispatcher used to notify subscribers of config import events.
   */
  public function __construct(EventDispatcherInterface $eventDispatcher) {
    $this->eventDispatcher = $eventDispatcher;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('event_dispatcher')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    $order = $this->entity->getOrder();
    $paymentGateway = $this->entity->getPaymentGateway();
    $configuration = $paymentGateway->getPlugin()->getConfiguration();

    // Prepopulate session data with defaults.
    $sessionData = [];
    if (!empty($configuration['configuration_handle'])) {
      $sessionData['configuration'] = $configuration['configuration_handle'];
    }
    if (!empty($configuration['locale'])) {
      $sessionData['locale'] = $configuration['locale'];
    }
    if (!empty($configuration['button_text'])) {
      $sessionData['button_text'] = $configuration['button_text'];
    }
    $sessionData['accept_url'] = $form['#return_url'];
    $sessionData['cancel_url'] = $form['#cancel_url'];

    try {
      // Invoke event to allow changes to the session data.
      $checkoutSessionEvent = new CheckoutSessionEvent($paymentGateway, $order, $sessionData);
      $this->eventDispatcher->dispatch(ReepayEvents::CREATE_CHECKOUT_SESSION, $checkoutSessionEvent);
      $sessionData = $checkoutSessionEvent->getSessionData();

      // Create the checkout session.
      $checkoutApi = new ReepayCheckoutApi($configuration['private_key']);
      if ($configuration['session_type'] == 'charge') {
        $checkoutSession = $checkoutApi->createChargeSession($sessionData);
      }
      else {
        $checkoutSession = $checkoutApi->createRecurringSession($sessionData);
      }
      if (!$checkoutSession instanceof ReepayCheckoutSession) {
        throw new WebhookException('No session returned from API');
      }
    }
    catch (\Exception $exception) {
      $this->getLogger('commerce_payment_reepay')->error(
        $this->t('Could not create checkout session for order @handle: @error', [], ['context' => 'Reepay']),
        ['@handle' => $order->id(), '@error' => $exception->getMessage()]
      );
      drupal_set_message($this->t('An error occured please try again or contact customer support.'), 'error');
      $this->redirectToPreviousStep();
    }

    if ($configuration['checkout_type'] == 'redirect') {
      return $this->buildRedirectForm($form, $form_state, $checkoutSession->getUrl(), [], self::REDIRECT_GET);
    }
    else {
      // @todo the js for each checkout_type should be implemented in js/reepay-checkout.js.
      $form['#attached']['library'][] = 'commerce_payment_reepay/checkout';
      $form['#attached']['drupalSettings'] = [
        'commerceReepayCheckout' => [
          'publicKey' => $configuration['public_key'],
          'checkoutType' => $configuration['checkout_type'],
          'cancelUrl' => $form['#cancel_url'],
          'returnUrl' => $form['#return_url'],
          'sessionID' => $checkoutSession->getId(),
        ],
      ];
    }

    return $form;
  }

  /**
   * Redirect to previous step of checkout.
   */
  protected function redirectToPreviousStep() {
    $routeMatch = \Drupal::routeMatch();
    $checkoutFlow = $this->entity->getOrder()->get('checkout_flow')->entity;
    $checkoutFlowPlugin = $checkoutFlow->getPlugin();
    $stepId = $routeMatch->getParameter('step');
    $redirectStepId = $checkoutFlowPlugin->getPreviousStepId($stepId);
    $checkoutFlowPlugin->redirectToStep($redirectStepId);
    // Method will never return because redirectToStep() will throw an
    // exception.
  }

}
