<?php

namespace HeidelGatewayPlenty\Providers;

use Plenty\Modules\Basket\Contracts\BasketRepositoryContract;
use Plenty\Modules\Basket\Events\Basket\AfterBasketChanged;
use Plenty\Modules\Basket\Events\Basket\AfterBasketCreate;
use Plenty\Modules\Basket\Events\BasketItem\AfterBasketItemAdd;
use Plenty\Modules\Payment\Events\Checkout\ExecutePayment;
use Plenty\Modules\Payment\Events\Checkout\GetPaymentMethodContent;
use Plenty\Modules\Payment\Method\Contracts\PaymentMethodContainer;
use Plenty\Plugin\Events\Dispatcher;
use Plenty\Plugin\ServiceProvider;

use Plenty\Plugin\ConfigRepository;

use HeidelGatewayPlenty\Helper\HeidelGatewayPlentyHelper;
use HeidelGatewayPlenty\Methods\HgwCreditcardPaymentMethod;

use \Heidelpay\PhpApi\PaymentMethods\CreditCardPaymentMethod;

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
        BasketRepositoryContract $warenkorb,
        ConfigRepository $configRepository,
        CreditCardPaymentMethod $cardPaymentMethod
    )
    {
        // Create the ID of the payment method if it doesn't exist yet
        $paymentHelper->createMopIfNotExists();

        /**
         * @todo hier alle Paymethoden Registrieren
         */

        // Register Creditcard payment method in the payment method container
        $payContainer->register('HeidelGatewayPlenty::HGWCREDITCARD', HgwCreditcardPaymentMethod::class, [AfterBasketChanged::class, AfterBasketItemAdd::class, AfterBasketCreate::class]);

        // Listen for the event that executes the payment
        $eventDispatcher->listen(ExecutePayment::class,
            function (ExecutePayment $event) use ($paymentHelper) {
                if ($event->getMop() == $paymentHelper->getPaymentMethod()) {

                    $event->setValue('<h1>Heidelpay ExecutePayment<h1>');
                    $event->setType('htmlContent');
                }
            });


        // Listen for the event that gets the payment method content
        $eventDispatcher->listen(GetPaymentMethodContent::class,
            function (GetPaymentMethodContent $event) use ($paymentHelper, $warenkorb, $configRepository, $cardPaymentMethod) {
                if ($event->getMop() == $paymentHelper->getPaymentMethod()) {
                    $warenkorb = $warenkorb->load();

                    $paramsToSend = array();
                    $paramsToSend[0] = $configRepository->get('HeidelGatewayPlenty.securitySender');
                    $paramsToSend[1] = $configRepository->get('HeidelGatewayPlenty.login');
                    $paramsToSend[2] = $configRepository->get('HeidelGatewayPlenty.password');
                    $paramsToSend[3] = $configRepository->get('HeidelGatewayPlenty.hgw_cc_channel');
                    $paramsToSend[4] = true;
                    if ($configRepository->get('HeidelGatewayPlenty.transactionmode')) {
                        $paramsToSend[4] = false;
                    }

                    $cardPaymentMethod->getRequest()->authentification($paramsToSend);

                    $cardPaymentMethod->getRequest()->customerAddress(
                        'John',
                        'Doe',
                        null,
                        '12345',
                        'Vangerowstr. 5',
                        null,
                        '69115',
                        'Heidelberg',
                        'Deutschland',
                        'sascha.pflueger@heidelpay.de'
                    );
                    $cardPaymentMethod->getRequest()->basketData(
                        '1234',
                        '15.30',
                        'EUR',
                        $configRepository->get('HeidelGatewayPlenty.secret')
                    );

                    $cardPaymentMethod->getRequest()->async(
                        'DE',
                        'https://heidelpay-dev.plentymarkets-cloud01.com'
                    );

					$cardPaymentMethod->authorize(
					    'https://heidelpay-dev.plentymarkets-cloud01.com',
                        'TRUE',
                        null
                    );

					if($cardPaymentMethod->getResponse()->isSuccess())
					{
					    $paymentformUrl = $cardPaymentMethod->getResponse()->getPaymentFormUrl();
                    }
						$event->setValue($paymentformUrl.'<br><h1>Heidelpay GetPaymentMethodContent<h1>' . $paramsToSend['USER.PWD'] . ' hier USR.Pass');
						$event->setType('htmlContent');
					}
            });


    }
}