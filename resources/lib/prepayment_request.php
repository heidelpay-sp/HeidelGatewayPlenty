<?php
$params = SdkRestApi::getParam("params");

try {
    $creditCardMethod = new \Heidelpay\PhpApi\PaymentMethods\CreditCardPaymentMethod();

    $creditCardMethod->getRequest()->authentification(...json_decode($params["authentification"]));
    $creditCardMethod->getRequest()->customerAddress(...json_decode($params["customerAddress"]));
    $creditCardMethod->getRequest()->basketData(...json_decode($params["basketData"]));
    $creditCardMethod->getRequest()->async(...json_decode($params["async"]));
    $creditCardMethod->authorize(...json_decode($params["authorize"]));

    return $creditCardMethod->getResponse()->getPresentation()->getAmount();

} catch (Exception $e){
    return " boom ".$e->getMessage();
}