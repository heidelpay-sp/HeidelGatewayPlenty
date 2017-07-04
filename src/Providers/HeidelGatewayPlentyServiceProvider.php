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
use Plenty\Modules\Plugin\Libs\Contracts\LibraryCallContract;

use Plenty\Plugin\ConfigRepository;

use HeidelGatewayPlenty\Helper\HeidelGatewayPlentyHelper;
use HeidelGatewayPlenty\Methods\HgwCreditcardPaymentMethod;
/* ************************************************************************************ */
use Plenty\Modules\Account\Address\Contracts\AddressRepositoryContract;
use Plenty\Modules\Account\Contact\Contracts\ContactRepositoryContract;
use Plenty\Modules\Account\Contact\Models\Contact;
/* ************************************************************************************ */
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
        LibraryCallContract $libCall

       //, AddressRepositoryContract $addressRepo
    )
    {
        // Create the ID of the payment method if it doesn't exist yet
        $paymentHelper->createMopIfNotExists();

        /**
         * @todo hier alle Paymethoden Registrieren
         */

        // Register Creditcard payment method in the payment method container
        $payContainer->register(
            'HeidelGatewayPlenty::HGWCREDITCARD',
            HgwCreditcardPaymentMethod::class,
            [AfterBasketChanged::class, AfterBasketItemAdd::class, AfterBasketCreate::class]
        );

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
            function (GetPaymentMethodContent $event) use ($paymentHelper, $warenkorb, $configRepository, $libCall /*, $addressRepo*/) {
                if ($event->getMop() == $paymentHelper->getPaymentMethod()) {
                    $warenkorb = $warenkorb->load();

                     /* ************************************************************************************ */
                    /* $shippingAddressId = $warenkorb->customerShippingAddressId;
                    if($shippingAddressId == -99)
                    {
                        $shippingAddressId = $warenkorb->customerInvoiceAddressId;
                    }
                    $adresse = $addressRepo->findAddressById($shippingAddressId);
                        $event->setValue('<h1>Heidelpay GetPaymentMethodContent<h1><br>'.$adresse->firstName);*/
                    /* ************************************************************************************ */
                        $creditcardRequest = $libCall->call(
                            "HeidelGatewayPlenty::creditcard_request",
                            $params = array(
                                "authentification" => [
                                    0 => $configRepository->get('HeidelGatewayPlenty.securitySender'),
                                    1 => $configRepository->get('HeidelGatewayPlenty.login'),
                                    2 => $configRepository->get('HeidelGatewayPlenty.password'),
                                    3 => $configRepository->get('HeidelGatewayPlenty.hgw_cc_channel'),
                                    4 => true
                                ],
                                "customerAddress" => [
                                    0 => "nameGiven",
                                    1 => "nameFamily",
                                    2 => "nameCompany",
                                    3 => "shopperId:",
                                    3 => "addressStreet",
                                    4 => "addressState",
                                    5 => "addressZip",
                                    6 => "addressCity",
                                    7 => "addressCountry",
                                    8 => "contactMail"
                                ],
                                "basketData" => [
                                    0 => "ShopIdentifier",
                                    1 => "amount",
                                    2 => "currency",
                                    3 => "secret"
                                ],
                                "async" => [
                                    0 => "languageCode",
                                    1 => "responseUrl"
                                ],
                                "authorize" => [
                                    0 => "paymentFrameOrigin",
                                    1 => "preventAsyncRedirect",
                                    2 => "cssPath"
                                ]
                            )

                            );

                        $event->setValue('<h1>Heidelpay GetPaymentMethodContent<h1><br>'.json_encode($creditcardRequest));
						$event->setType('htmlContent');
					}
            });


    }
}