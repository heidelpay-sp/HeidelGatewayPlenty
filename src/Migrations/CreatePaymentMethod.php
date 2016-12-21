<?php
namespace HeidelGatewayPlenty\Migrations;
use Plenty\Modules\Payment\Method\Contracts\PaymentMethodRepositoryContract;
use HeidelGatewayPlenty\Helper\PaymentHelper;
/**
 * Migration to create payment mehtods
 *
 * Class CreatePaymentMethod
 * @package HeidelGatewayPlenty\Migrations
 */
class CreatePaymentMethod
{
	/**
	 * @var PaymentMethodRepositoryContract
	 */
	private $paymentMethodRepositoryContract;
	/**
	 * @var PaymentHelper
	 */
	private $paymentHelper;
	/**
	 * CreatePaymentMethod constructor.
	 *
	 * @param PaymentMethodRepositoryContract $paymentMethodRepositoryContract
	 * @param PaymentHelper $paymentHelper
	 */
	public function __construct(
			PaymentMethodRepositoryContract $paymentMethodRepositoryContract,
			PaymentHelper $paymentHelper) {
				$this->paymentMethodRepositoryContract = $paymentMethodRepositoryContract;
				$this->paymentHelper = $paymentHelper;
	}
	/**
	 * Run on plugin build
	 *
	 * Create Method of Payment ID for PayPal and PayPal Express if they don't exist
	 */
	public function run()
	{
		// Check whether the ID of the PayPal payment method has been created
		if($this->paymentHelper->getPaymentMethod() == 'no_paymentmethod_found')
		{
			$paymentMethodData = array( 
					'pluginKey' => 'HeidelGatewayPlenty',
					'paymentKey' => 'CREDITCARD',
					'name' => 'Heidelpay CD-Edition Kreditkarte');
			$this->paymentMethodRepositoryContract->createPaymentMethod($paymentMethodData);
		}
		// Check whether the ID of the PayPal Express payment method has been created
		
	}
}