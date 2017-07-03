<?php
use Plenty\Plugin\ConfigRepository;

        $configRepository = new ConfigRepository;


        $cardPaymentMethod = new \Heidelpay\PhpApi\PaymentMethods\CreditCardPaymentMethod();
        $paramsToSend = array();
        $paramsToSend[0] = $configRepository->get('HeidelGatewayPlenty.securitySender');
        $paramsToSend[1] = $configRepository->get('HeidelGatewayPlenty.login');
        $paramsToSend[2] = $configRepository->get('HeidelGatewayPlenty.password');
        $paramsToSend[3] = $configRepository->get('HeidelGatewayPlenty.hgw_cc_channel');
        $paramsToSend[4] = true;
        if ($configRepository->get('HeidelGatewayPlenty.transactionmode')) {
            $paramsToSend[4] = false;
        }

        $cardPaymentMethod->getRequest()->authentification($paramsToSend);

        $cardPaymentMethod->getRequest()->customerAddress(
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
        $cardPaymentMethod->getRequest()->basketData(
            '1234',
            '15.30',
            'EUR',
            $configRepository->get('HeidelGatewayPlenty.secret')
        );

        $cardPaymentMethod->getRequest()->async(
            'DE',
            'https://heidelpay-dev.plentymarkets-cloud01.com'
        );

        $cardPaymentMethod->authorize(
            'https://heidelpay-dev.plentymarkets-cloud01.com',
            'TRUE',
            null
        );
return $cardPaymentMethod->getResponse()->getPresentation()->getAmount();
       /* if($cardPaymentMethod->getResponse()->isSuccess())
        {
           return $cardPaymentMethod->getRequest()->getPresentation()->getAmount();

        } else {
            return "schade";
        }
*/
