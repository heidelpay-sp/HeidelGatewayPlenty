<?php

use Plenty\Modules\Frontend\Services\AccountService;

/*
 * all given parameters are converted to strings because plenty uses http in general
 */
try {
    $prepaymentPaymentMethod = new \Heidelpay\PhpApi\PaymentMethods\PrepaymentPaymentMethod();

    $params = SdkRestApi::getParam("authentification");
    $prepaymentPaymentMethod->getRequest()->authentification(
        $params[0],
        $params[1],
        $params[2],
        $params[3],
        ($params[4] == 'false') ? false : true
    );

    $params = SdkRestApi::getParam("customerAddress");
    $prepaymentPaymentMethod->getRequest()->customerAddress(
        $params[0],
        $params[1],
        $params[2],
        $params[3],
        $params[4],
        $params[5],
        $params[6],
        $params[7],
        $params[8],
        $params[9]
    );

    $params = SdkRestApi::getParam("basketData");
    $prepaymentPaymentMethod->getRequest()->basketData(
        $params[0],
        $params[1],
        $params[2],
        $params[3]
    );

    $params = SdkRestApi::getParam("async");
    $prepaymentPaymentMethod->getRequest()->async(
        $params[0],
        $params[1]
    );

    $prepaymentPaymentMethod->getRequest()->getFrontend()->set('enabled','FALSE');

    $prepaymentPaymentMethod->authorize();

//    $paramsAuth         = SdkRestApi::getParam("authentification");
//    $paramsCustomerData = SdkRestApi::getParam("customerAddress");
//    $paramsBasketData   = SdkRestApi::getParam("basketData");
//    $paramsAsync        = SdkRestApi::getParam("async");

//    return
//        $currentContactId
//        $paramsAuth
//        $paramsCustomerData
//        $paramsBasketData
//        $paramsAsync
//        ;

    return json_encode($prepaymentPaymentMethod->toJson());
} catch (Exception $e){
    return " boom ".$e->getMessage();
}