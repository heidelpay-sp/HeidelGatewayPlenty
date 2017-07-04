<?php
//use Plenty\Plugin\ConfigRepository;

try {

    //$configRepository = new ConfigRepository;
    $configRepository = new \Plenty\Plugin\ConfigRepository;
    return 'hallo';
    // Filling Request-Object with Data
    $creditCardMethod = new \Heidelpay\PhpApi\PaymentMethods\CreditCardPaymentMethod();
    $paramsToSend = array();
    $paramsToSend[0] = $configRepository->get('HeidelGatewayPlenty.securitySender');
    $paramsToSend[1] = $configRepository->get('HeidelGatewayPlenty.login');
    $paramsToSend[2] = $configRepository->get('HeidelGatewayPlenty.password');
    $paramsToSend[3] = $configRepository->get('HeidelGatewayPlenty.hgw_cc_channel');
    $paramsToSend[4] = true;

    if ($configRepository->get('HeidelGatewayPlenty.transactionmode')) {
        $paramsToSend[4] = false;
    }

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
        $configRepository->get('HeidelGatewayPlenty.secret')
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