<?php


//$params = json_decode($params,true);
try {
    $prepaymentPaymentMethod = new \Heidelpay\PhpApi\PaymentMethods\PrepaymentPaymentMethod();

    $params = SdkRestApi::getParam("authentification");
    $prepaymentPaymentMethod->getRequest()->authentification(
        $params[0],
        $params[1],
        $params[2],
        $params[3]
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

//    $prepaymentPaymentMethod->getRequest()->getFrontend()->set('enabled','FALSE');

    $prepaymentPaymentMethod->authorize();

    $params0 = SdkRestApi::getParam("authentification");
    $params1 = SdkRestApi::getParam("customerAddress");
    $params2 = SdkRestApi::getParam("basketData");
    $params3 = SdkRestApi::getParam("async");


    return json_encode($params0.$params1.$params2.$params3);

    return json_encode($prepaymentPaymentMethod->getResponse()->getError());
//    return $params["authentification"][0].' '.$params["authentification"][1].' '.$params["authentification"][2].' '.$params["authentification"][3];

} catch (Exception $e){
    return " boom ".$e->getMessage();
}