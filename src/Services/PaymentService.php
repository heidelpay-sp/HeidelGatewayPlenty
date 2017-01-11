<?php
namespace HeidelGatewayPlenty\Services;

use Plenty\Modules\Basket\Models\BasketItem;
use Plenty\Modules\Payment\Contracts\PaymentRepositoryContract;
use Plenty\Modules\Payment\Method\Contracts\PaymentMethodRepositoryContract;
use Plenty\Modules\Payment\Models\Payment;
use Plenty\Modules\Basket\Models\Basket;
use Plenty\Modules\Plugin\Libs\Contracts\LibraryCallContract;
use Plenty\Plugin\ConfigRepository;
use Plenty\Modules\Account\Address\Contracts\AddressRepositoryContract;

use PayPal\Helper\PaymentHelper;

class PaymentService {
	/**
     * @var string
     */
    private $returnType = '';

    /**
     * @var PaymentMethodRepositoryContract
     */
    private $paymentMethodRepository;

    /**
     * @var PaymentRepositoryContract
     */
    private $paymentRepository;

    /**
     * @var PaymentHelper
     */
    private $paymentHelper;

    /**
     * @var LibraryCallContract
     */
    private $libCall;

    /**
     * @var AddressRepositoryContract
     */
    private $addressRepo;

    /**
     * @var ConfigRepository
     */
    private $config;

	
	
	public function __construct(  
			PaymentMethodRepositoryContract $paymentMethodRepository,
			PaymentRepositoryContract $paymentRepository,
			ConfigRepository $config,
			PaymentHelper $paymentHelper,
			LibraryCallContract $libCall,
			AddressRepositoryContract $addressRepo)
			
	{
		$this->paymentMethodRepository    = $paymentMethodRepository;
		$this->paymentRepository          = $paymentRepository;
		$this->paymentHelper              = $paymentHelper;
		$this->libCall                    = $libCall;
		$this->addressRepo                = $addressRepo;
		$this->config                     = $config;
		
	}
	
	
	public function getConfigParams() {
		$channel = $this->config->get('HeidelGatewayPlenty.hgw_ccChannel');
		return $channel;
	}
}