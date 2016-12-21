<?php

namespace HeidelGatewayPlenty\Providers;

use Plenty\Plugin\ServiceProvider;
use Plenty\Plugin\Events\Dispatcher;
use Plenty\Modules\Payment\Events\Checkout\ExecutePayment;
use Plenty\Modules\Payment\Events\Checkout\GetPaymentMethodContent;
use Plenty\Modules\Basket\Contracts\BasketRepositoryContract;
use Plenty\Modules\Basket\Events\Basket\AfterBasketCreate;
use Plenty\Modules\Basket\Events\Basket\AfterBasketChanged;
use Plenty\Modules\Basket\Events\BasketItem\AfterBasketItemAdd;
use Plenty\Modules\Payment\Method\Contracts\PaymentMethodContainer;

use HeidelGatewayPlenty\Helper\HeidelGatewayPlentyHelper;
use HeidelGatewayPlenty\Methods\CreditcardPaymentMethod;

/**
 * Class PayUponPickupServiceProvider
 * @package PayUponPickup\Providers
 */
class HeidelGatewayPlentyServiceProvider extends ServiceProvider
{
	public function register()
	{

	}

	/**
	 * Boot additional services for the payment method
	 *
	 * @param HeidelGatewayPlentyHelper $paymentHelper
	 * @param PaymentMethodContainer $payContainer
	 * @param Dispatcher $eventDispatcher
	 */
	public function boot( 
			HeidelGatewayPlentyHelper $paymentHelper,
			PaymentMethodContainer $payContainer,
			Dispatcher $eventDispatcher,
			BasketRepositoryContract $warenkorb
			)
	{
		// Create the ID of the payment method if it doesn't exist yet
		$paymentHelper->createMopIfNotExists();
		
		/**
		 * @todo hier alle Paymethoden Registrieren
		 */
		
		// Register Creditcard payment method in the payment method container
		$payContainer->register('HeidelGatewayPlenty::CREDITCARD', CreditcardPaymentMethod::class, [ AfterBasketChanged::class, AfterBasketItemAdd::class, AfterBasketCreate::class ]);
				
		// Listen for the event that executes the payment
		$eventDispatcher->listen(ExecutePayment::class,
				function(ExecutePayment $event) use( $paymentHelper)
				{
					if($event->getMop() == $paymentHelper->getPaymentMethod())
					{
						
						$event->setValue('<h1>Heidelpay CD-Edition Kreditkarte<h1>');
						$event->setType('htmlContent');
					}
				});
		
		
		// Listen for the event that gets the payment method content
		$eventDispatcher->listen(GetPaymentMethodContent::class,
				function(GetPaymentMethodContent $event) use( $paymentHelper, $warenkorb)
				{
					if($event->getMop() == $paymentHelper->getPaymentMethod())
					{
						$warenkorb = $warenkorb->load();
						$event->setValue('');
						$event->setType('continue');
					}
				});

		
	}
}