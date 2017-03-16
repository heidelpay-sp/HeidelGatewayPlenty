<?php
namespace HeidelGatewayPlenty\Methods;

use Plenty\Plugin\ConfigRepository;
// use Plenty\Modules\Account\Contact\Contracts\ContactRepositoryContract;
use Plenty\Modules\Payment\Method\Contracts\PaymentMethodService;
use Plenty\Modules\Basket\Contracts\BasketRepositoryContract;
// use Plenty\Modules\Basket\Models\Basket;


/**
 * Class PayUponPickupPaymentMethod
 * @package PayUponPickup\Methods
 */
class HgwCreditcardPaymentMethod extends PaymentMethodService
{
	
	/**
	 * @var BasketRepositoryContract
	 */
	private $basketRepository;
	
	/**
	 * @var ContactRepositoryContract
	 */
// 	private $contactRepository;
	
	/**
	 * @var ConfigRepository
	 */
	private $configRepository;
	
	
	public function __construct(
			BasketRepositoryContract    $basketRepository,
// 			ContactRepositoryContract   $contactRepository,
			ConfigRepository            $configRepository
			)
	{
		$this->basketRepository     = $basketRepository;
// 		$this->contactRepository    = $contactRepository;
		$this->configRepository     = $configRepository;
	}
	
	/**
	 * Check the configuration if the payment method is active
	 * Return true if the payment method is active, else return false
	 *
	 * @param ConfigRepository $configRepository
	 * @param BasketRepositoryContract $basketRepositoryContract
	 * @return bool
	 */
	public function isActive( 
			ConfigRepository $configRepository,
			BasketRepositoryContract $basketRepositoryContract):bool{
				/** @var bool $active */
				
				$active = $configRepository->get('HeidelGatewayPlenty.hgw_cc_active');
								
				return $active;
	}

	/**
	 * Get the name of the payment method. The name can be entered in the config.json.
	 *
	 * @param ConfigRepository $configRepository
	 * @return string
	 */
	public function getName( ConfigRepository $configRepository )
	{
		$name = $configRepository->get('HeidelGatewayPlenty.hgw_cc_name');
		
		if(!strlen($name))
		{
			$name = "Heidelpay CD-Edition Kreditkarte";
		}

		return $name;

	}

	/**
	 * Get the path of the icon. The URL can be entered in the config.json.
	 *
	 * @param ConfigRepository $configRepository
	 * @return string
	 */
	public function getIcon( ConfigRepository $configRepository ):string
	{	
		$icon = '';
		if($configRepository->get('HeidelGatewayPlenty.basicDatahgw_cclogo') == 1)
		{
			$icon = $this->configRepository->get('HeidelGatewayPlenty.basicDatahgw_cclogo');
		} else {
			$icon ='layout/plugins/production/heidelgatewayplenty/images/logos/hplog.png';
		}
		return $icon;
	}

	/**
	 * Get the description of the payment method. The description can be entered in the config.json.
	 *
	 * @param ConfigRepository $configRepository
	 * @return string
	 */
	public function getDescription( ConfigRepository $configRepository ):string
	{
		return 'Testbeschreibung';
	}
}