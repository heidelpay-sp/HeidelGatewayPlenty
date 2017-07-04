<?php
$params = SdkRestApi::getParam("params");
return $params;
//$params = json_decode($params,true);
try {
    $creditCardMethod = new \Heidelpay\PhpApi\PaymentMethods\PrepaymentPaymentMethod();

    $creditCardMethod->getRequest()->authentification(
        $params["authentification"][0],
        $params["authentification"][1],
        $params["authentification"][2],
        $params["authentification"][3]
    );

    $creditCardMethod->getRequest()->customerAddress(
        $params["customerAddress"][0],
        $params["customerAddress"][1],
        $params["customerAddress"][2],
        $params["customerAddress"][3],
        $params["customerAddress"][4],
        $params["customerAddress"][5],
        $params["customerAddress"][6],
        $params["customerAddress"][7],
        $params["customerAddress"][8]
    );

    $creditCardMethod->getRequest()->basketData(
        $params["basketData"][0],
        $params["basketData"][1],
        $params["basketData"][2],
        $params["basketData"][3]
    );
    $creditCardMethod->getRequest()->async(
        $params["async"][0],
        $params["async"][1]
    );
    $creditCardMethod->authorize(
//        $params["authorize"][0],
//        $params["authorize"][1],
//        $params["authorize"][2]
    );

//    return $creditCardMethod->getResponse()->getPresentation()->getAmount();
    return $params["authentification"][0].' '.$params["authentification"][1].' '.$params["authentification"][2].' '.$params["authentification"][3];

} catch (Exception $e){
    return " boom ".$e->getMessage();
}