<?php
namespace HeidelGatewayPlenty\Providers;

use Plenty\Plugin\RouteServiceProvider;
use Plenty\Plugin\Routing\Router;

/**
 * Class PayPalRouteServiceProvider
 * @package PayPal\Providers
 */
class PayPalRouteServiceProvider extends RouteServiceProvider
{
	/**
	 * @param Router $router
	 */
	public function map(Router $router)
	{
		// Get the PayPal success and cancellation URLs
		$router->get('HeidelGatewayPlenty/checkoutSuccess'	, 'HeidelGatewayPlenty\Controllers\PaymentController@checkoutSuccess');
		$router->get('HeidelGatewayPlenty/checkoutCancel' 	, 'HeidelGatewayPlenty\Controllers\PaymentController@checkoutCancel' );
		$router->post('HeidelGatewayPlenty/notification'  	, 'HeidelGatewayPlenty\Controllers\PaymentNotificationController@handleNotification');
	}



}