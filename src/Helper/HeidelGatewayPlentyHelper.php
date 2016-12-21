<?php
namespace HeidelGatewayPlenty\Helper;

use Plenty\Modules\Payment\Method\Contracts\PaymentMethodRepositoryContract;
use Plenty\Modules\Payment\Method\Models\PaymentMethod;

/**
 * Class HeidelGatewayPlentyHelper
 *
 * @package HeidelGatewayPlenty\Helper
 */
class HeidelGatewayPlentyHelper
{
	/**
	 * @var PaymentMethodRepositoryContract $paymentMethodRepository
	 */
	private $paymentMethodRepository;

	/**
	 * HeidelGatewayPlentyHelper constructor.
	 *
	 * @param PaymentMethodRepositoryContract $paymentMethodRepository
	 */
	public function __construct(PaymentMethodRepositoryContract $paymentMethodRepository)
	{
		$this->paymentMethodRepository = $paymentMethodRepository;
	}

	/**
	 * Create the ID of the payment method if it doesn't exist yet
	 */
	public function createMopIfNotExists()
	{
		// Check whether the ID of the HeidelGatewayPlenty payment method has been created
		/**
		 * @todo foreach einbauen und nach allen Paymethods suchen
		 * @todo PaymentKeys und name Festlegen
		 */
		if($this->getPaymentMethod() == 'no_paymentmethod_found')
		{
			$paymentMethodData = array( 
					'pluginKey' 	=> 'HeidelGatewayPlenty',
					'paymentKey' 	=> 'CREDITCARD',
					'name' 			=> 'Heidelpay CD-Edition Kreditkarte'
					
			);

			$this->paymentMethodRepository->createPaymentMethod($paymentMethodData);
		}
	}

	/**
	 * Load the ID of the payment method for the given plugin key
	 * Return the ID for the payment method
	 *
	 * @return string|int
	 */
	public function getPaymentMethod()
	{
		$paymentMethods = $this->paymentMethodRepository->allForPlugin('HeidelGatewayPlenty');

		if( !is_null($paymentMethods) )
		{
			foreach($paymentMethods as $paymentMethod)
			{
				if($paymentMethod->paymentKey == 'CREDITCARD')
				{
					return $paymentMethod->id;
				}
			}
		}

		return 'no_paymentmethod_found';
	}
}