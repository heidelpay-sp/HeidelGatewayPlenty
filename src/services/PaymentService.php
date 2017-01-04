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

use HeidelGatewayPlenty\Helper\PaymentHelper;

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
	
	/**
	 * @var SessionStorageService
	 */
	private $sessionStorage;
	
	/**
	 * @var ContactService
	 */
	private $contactService;
	
	/**
	 * PaymentService constructor.
	 *
	 * @param PaymentMethodRepositoryContract $paymentMethodRepository
	 * @param PaymentRepositoryContract $paymentRepository
	 * @param ConfigRepository $config
	 * @param PaymentHelper $paymentHelper
	 * @param LibraryCallContract $libCall
	 * @param AddressRepositoryContract $addressRepo
	 * @param SessionStorageService $sessionStorage
	 */
	public function __construct(  
			PaymentMethodRepositoryContract $paymentMethodRepository,
			PaymentRepositoryContract $paymentRepository,
			ConfigRepository $config,
			PaymentHelper $paymentHelper,
			LibraryCallContract $libCall,
			AddressRepositoryContract $addressRepo,
			SessionStorageService $sessionStorage,
			ContactService $contactService
			)
	{
		$this->paymentMethodRepository    = $paymentMethodRepository;
		$this->paymentRepository          = $paymentRepository;
		$this->paymentHelper              = $paymentHelper;
		$this->libCall                    = $libCall;
		$this->addressRepo                = $addressRepo;
		$this->config                     = $config;
		$this->sessionStorage             = $sessionStorage;
		$this->contactService             = $contactService;
	}
	
	public function getPaymentContent(Basket $basket, $mode = 'CREDITCARD'):string
	{
		// Get the content of the PayPal container
		$paymentContent = '';
		$links = $resultJson->links;
		if(is_array($links))
		{
			foreach($links as $key => $value)
			{
				// Get the redirect URLs for the content
				if($value->method == 'REDIRECT')
				{
					$paymentContent = $value->href;
					$this->returnType = 'redirectUrl';
				}
			}
		}
		// Check whether the content is set. Else, return an error code.
		if(!strlen($paymentContent))
		{
			$this->returnType = 'errorCode';
			return 'An unknown error occurred in PaymentService Class, please try again.';
		}
		return $paymentContent;
	}
	
	
} // End of class