<?php
$params = SdkRestApi::getParam("params");

try {
    $creditCardMethod = new \Heidelpay\PhpApi\PaymentMethods\CreditCardPaymentMethod();

    $creditCardMethod->getRequest()->authentification($params["authentification"]);
    $creditCardMethod->getRequest()->customerAddress($params["customerAddress"]);
    $creditCardMethod->getRequest()->basketData($params["basketData"]);
    $creditCardMethod->getRequest()->async($params["async"]);
    $creditCardMethod->authorize($params["authorize"]);

    return $creditCardMethod->getResponse()->getPresentation()->getAmount();

} catch (Exception $e){
    return " boom ".$e->getMessage();
}