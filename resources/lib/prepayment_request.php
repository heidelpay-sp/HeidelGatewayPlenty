<?php
use Plenty\Plugin\ConfigRepository;

$configRepository = new ConfigRepository;

// Filling Request-Object with Data
$prePaymentMethod = new \Heidelpay\PhpApi\PaymentMethods\PrepaymentPaymentMethod();
$paramsToSend = array();
$paramsToSend[0] = $configRepository->get('HeidelGatewayPlenty.securitySender');
$paramsToSend[1] = $configRepository->get('HeidelGatewayPlenty.login');
$paramsToSend[2] = $configRepository->get('HeidelGatewayPlenty.password');
$paramsToSend[3] = $configRepository->get('HeidelGatewayPlenty.hgw_cc_channel');
$paramsToSend[4] = true;

if ($configRepository->get('HeidelGatewayPlenty.transactionmode')) {
    $paramsToSend[4] = false;
}

$prePaymentMethod->getRequest()->authentification($paramsToSend);

$prePaymentMethod->getRequest()->customerAddress(
    "John",
    "Doe",
    "",
    "12345",
    "Vangerowstr. 5",
    null,
    '69115',
    'Heidelberg',
    'Deutschland',
    'sascha.pflueger@heidelpay.de'
);

$prePaymentMethod->getRequest()->basketData(
    '1234',
    '15.30',
    'EUR',
    $configRepository->get('HeidelGatewayPlenty.secret')
);

$prePaymentMethod->getRequest()->async(
    'DE',
    'https://heidelpay-dev.plentymarkets-cloud01.com'
);

$prePaymentMethod-> authorize(
    'https://heidelpay-dev.plentymarkets-cloud01.com',
    'TRUE',
    null
);

return $prePaymentMethod->getResponse()->getPresentation()->getAmount();
