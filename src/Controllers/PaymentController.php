<?php
/**
 * Created by PhpStorm.
 * User: Sascha.Pflueger
 * Date: 07.07.2017
 * Time: 10:41
 */

namespace HeidelGatewayPlenty\Controllers;

use Plenty\Plugin\ConfigRepository;
use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;

/**
 * Class PaymentController
 * @package HeidelGatewayPlenty\Controllers
 */
class PaymentController extends Controller
{
    /**
    * @var Request
    */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var ConfigRepository
     */
    private $config;

    public function __construct(
        Request $request,
        Response $response,
        ConfigRepository $config
    )
    {
        $this->request   = $request;
        $this->response  = $response;
        $this->config    = $config;

    }

    public function checkoutCancel()
    {

    }

    public function checkoutSuccess()
    {

    }

    public function responseAction($response)
    {
        return $response;
    }
}