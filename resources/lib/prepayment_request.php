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

    $prepaymentPaymentMethod->getRequest()->getFrontend()->set('enabled','FALSE');

    $prepaymentPaymentMethod->authorize();
    /**
     *
     */
//    $params = SdkRestApi::getParam("customerAddress");
//    return "Vorname: ".$params[0]."</br>Nachname: ".$params[1]."</br>Company: ".$params[2].
//        "</br>KundenNr: ".$params[3]."</br>Straße: ".$params[4]."</br> Null:".$params[5].
//        "</br>Plz: ".$params[6]."</br>Ort: ".$params[7]."</br>Country: ".$params[8].
//        "</br>Email: ".$params[9];
    /**
     *
     */
    return json_encode($prepaymentPaymentMethod->getResponse()->getError());
//    return $params["authentification"][0].' '.$params["authentification"][1].' '.$params["authentification"][2].' '.$params["authentification"][3];

} catch (Exception $e){
    return " boom ".$e->getMessage();
}