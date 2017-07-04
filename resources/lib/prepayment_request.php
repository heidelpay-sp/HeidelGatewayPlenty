<?php


//$params = json_decode($params,true);
try {
    $creditCardMethod = new \Heidelpay\PhpApi\PaymentMethods\PrepaymentPaymentMethod();

    $params = SdkRestApi::getParam("authentification");
    $creditCardMethod->getRequest()->authentification(
        $params[0],
        $params[1],
        $params[2],
        $params[3]
    );

    $params = SdkRestApi::getParam("customerAddress");
    $creditCardMethod->getRequest()->customerAddress(
        $params[0],
        $params[1],
        $params[2],
        $params[3],
        $params[4],
        $params[5],
        $params[6],
        $params[7],
        $params[8]
    );

    $params = SdkRestApi::getParam("basketData");
    $creditCardMethod->getRequest()->basketData(
        $params[0],
        $params[1],
        $params[2],
        $params[3]
    );

    $params = SdkRestApi::getParam("async");
    $creditCardMethod->getRequest()->async(
        $params[0],
        $params[1]
    );
    $creditCardMethod->authorize(
//        $params["authorize"][0],
//        $params["authorize"][1],
//        $params["authorize"][2]
    );

    return $creditCardMethod->getResponse()->getPresentation()->getAmount();
//    return $params["authentification"][0].' '.$params["authentification"][1].' '.$params["authentification"][2].' '.$params["authentification"][3];

} catch (Exception $e){
    return " boom ".$e->getMessage();
}