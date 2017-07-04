<?php
//use Plenty\Plugin\ConfigRepository;
//$configRepository = new ConfigRepository;

try {

    //$configRepository = new ConfigRepository;
   // $configRepository = new \Plenty\Plugin\ConfigRepository;

    // Filling Request-Object with Data
//    $creditCardMethod = new \Heidelpay\PhpApi\PaymentMethods\CreditCardPaymentMethod();
//    $paramsToSend = array();
//    $paramsToSend[0] = "31HA07BC8142C5A171745D00AD63D182";//$configRepository->get('HeidelGatewayPlenty.securitySender');
//    $paramsToSend[1] = "31ha07bc8142c5a171744e5aef11ffd3";//$configRepository->get('HeidelGatewayPlenty.login');
//    $paramsToSend[2] = "93167DE7";//$configRepository->get('HeidelGatewayPlenty.password');
//    $paramsToSend[3] = "31HA07BC8142C5A171744F3D6D155865";//$configRepository->get('HeidelGatewayPlenty.hgw_cc_channel');
//    $paramsToSend[4] = true;
//
//   // if ($configRepository->get('HeidelGatewayPlenty.transactionmode')) {
//        $paramsToSend[4] = false;
//    //}
    $params = SdkRestApi::getParam("params");

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