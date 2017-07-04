<?php
//use Plenty\Plugin\ConfigRepository;

try {

    //$configRepository = new ConfigRepository;
   // $configRepository = new \Plenty\Plugin\ConfigRepository;

    // Filling Request-Object with Data
    $creditCardMethod = new \Heidelpay\PhpApi\PaymentMethods\CreditCardPaymentMethod();
    $paramsToSend = array();
    $paramsToSend[0] = "31HA07BC8142C5A171745D00AD63D182";//$configRepository->get('HeidelGatewayPlenty.securitySender');
    $paramsToSend[1] = "31ha07bc8142c5a171744e5aef11ffd3";//$configRepository->get('HeidelGatewayPlenty.login');
    $paramsToSend[2] = "93167DE7";//$configRepository->get('HeidelGatewayPlenty.password');
    $paramsToSend[3] = "31HA07BC8142C5A171744F3D6D155865";//$configRepository->get('HeidelGatewayPlenty.hgw_cc_channel');
    $paramsToSend[4] = true;

   // if ($configRepository->get('HeidelGatewayPlenty.transactionmode')) {
        $paramsToSend[4] = false;
    //}

    $creditCardMethod->getRequest()->authentification($paramsToSend);

    $creditCardMethod->getRequest()->customerAddress(
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

    $creditCardMethod->getRequest()->basketData(
        '1234',
        '15.30',
        'EUR',
        "ChangeMe" //$configRepository->get('HeidelGatewayPlenty.secret')
    );

    $creditCardMethod->getRequest()->async(
        'DE',
        'https://heidelpay-dev.plentymarkets-cloud01.com'
    );

    $creditCardMethod->authorize(
        'https://heidelpay-dev.plentymarkets-cloud01.com',
        'TRUE',
        null
    );
  //  return 'hallo1';
    return $creditCardMethod->getResponse()->getPresentation()->getAmount();
    /* if($cardPaymentMethod->getResponse()->isSuccess())
     {
        return $cardPaymentMethod->getRequest()->getPresentation()->getAmount();

     } else {
         return "schade";
     }
*/
} catch (Exception $e){
    return " boom ".$e->getMessage();
}