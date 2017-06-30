<?php
namespace Heidelpay\Tests\PhpApi\Unit\PaymentMethodes;
use PHPUnit\Framework\TestCase;
use \Heidelpay\PhpApi\PaymentMethodes\PrepaymentPaymentMethod as  Prepayment;
/**
 *
 *  Prepayment Test
 *
 *  Connection tests can fail due to network issues and scheduled downtimes.
 *  This does not have to mean that your integration is broken. Please verify the given debug information
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 * @link  https://dev.heidelpay.de/PhpApi
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpApi
 * @category UnitTest
 */

class PrepaymentPaymentMerhodTest extends TestCase
{
    /** 
     * SecuritySender
     * @var string SecuritySender
     */
    protected $SecuritySender = '31HA07BC8142C5A171745D00AD63D182'; 
    /**
     * UserLogin
     * @var string UserLogin
     */
    protected $UserLogin      = '31ha07bc8142c5a171744e5aef11ffd3'; 
    /**
     * UserPassword
     * @var string UserPassword
     */
    protected $UserPassword   = '93167DE7';
    /**
     * TransactionChannel
     * 
     * @var string TransactionChannel
     */
    protected $TransactionChannel = '31HA07BC8142C5A171749A60D979B6E4';
    /**
     * SandboxRequest
     * 
     * Request will be send to Heidelpay sandbox payment system.
     * 
     * @var string
     */
    protected $SandboxRequest = TRUE ;
    
    /**
     * Customer given name
     * @var string nameGiven
     */
    protected $nameGiven = 'Heidel';
    /**
     * Customer family name
     * @var string nameFamily
     */
    protected $nameFamily ='Berger-Payment';
    /**
     * Customer company name
     * @var string nameCompany
     */
    protected $nameCompany = 'DevHeidelpay';
    /**
     * Customer id
     * @var string shopperId
     */
    protected $shopperId = '12344';
    /**
     * customer billing address street
     * @var string addressStreet
     */
    protected $addressStreet = 'Vagerowstr. 18';
    /**
     * customer billing address state
     * @var string addressState
     */
    protected $addressState  = 'DE-BW';
    /**
     * customer billing address zip
     * @var string addressZip
     */
    protected $addressZip    = '69115';
    /**
     * customer billing address city
     * @var string addressCity
     */
    protected $addressCity    = 'Heidelberg';
    /**
     * customer billing address city
     * @var string addressCity
     */
    protected $addressCountry = 'DE';
    /**
     * customer mail address
     * @var string contactMail
     */
    protected $contactMail = "development@heidelpay.de";
    
    /**
     * Transaction currency
     * @var string currency
     */
    
    protected   $currency = 'EUR';
    /**
     * Secret
     * 
     * The secret will be used to generate a hash using 
     * transaction id + secret. This hash can be used to
     * verify the the payment response. Can be used for
     * brute force protection.
     * @var string secret
     */
    protected   $secret = 'Heidelpay-PhpApi';
    
    /**
     * PaymentObject
     * @var \Heidelpay\PhpApi\PaymentMethodes\PrepaymentPaymentMethod
     */
    protected $paymentObject = NULL;
    /**
     * Constructor used to set timezone to utc
     */
  public function __construct() {
      date_default_timezone_set('UTC');
  }
  /**
   * Set up function will create a prepaymet object for each testcase
   * @see PHPUnit_Framework_TestCase::setUp()
   */
  public function  setUp() {
  	$Prepayment = new Prepayment();
  	
  	$Prepayment->getRequest()->authentification($this->SecuritySender, $this->UserLogin, $this->UserPassword, $this->TransactionChannel, 'TRUE');
  	
  	$Prepayment->getRequest()->customerAddress($this->nameGiven, $this->nameFamily, NULL, $this->shopperId, $this->addressStreet,$this->addressState,$this->addressZip, $this->addressCity, $this->addressCountry, $this->contactMail);
  	
  	
  	$Prepayment->_dryRun=TRUE;
  	
  	$this->paymentObject = $Prepayment;
  	
  }
  
  /**
   * Get current called method, without namespace
   * @param string $method
   * @return string class and method
   */
  public function getMethod($method) {
      return substr(strrchr($method, '\\'), 1);
  }
    
  /**
   * Test case for a single prepayment authorisation
   * @return string payment reference id for the prepayment authorize transaction
   * @group connectionTest
   */
  public function testAuthorize()
  {
      $timestamp = $this->getMethod(__METHOD__)." ".date("Y-m-d H:i:s");
      $this->paymentObject->getRequest()->basketData($timestamp, 23.12, $this->currency, $this->secret);
      $this->paymentObject->getRequest()->getFrontend()->set('enabled','FALSE');
      
  	  $this->paymentObject->authorize();
  	  
  	  /* prepare request and send it to payment api */
      $request =  $this->paymentObject->getRequest()->prepareRequest();
      $response =  $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);
      
      $this->assertTrue($response[1]->isSuccess(), 'Transaction failed : '.print_r($response[1],1));
      $this->assertFalse($response[1]->isPending(), 'authorize is pending');
      $this->assertFalse($response[1]->isError(),'authorize failed : '.print_r($response[1]->getError(),1));
      
      return (string)$response[1]->getPaymentReferenceId();
  }
  
  /**
   * Test case for a prepayment reversal of a existing authorisation
   * @var string payment reference id of the prepayment authorisation
   * @return string payment reference id for the prepayment reversal transaction
   * @depends testAuthorize
   * @group connectionTest
   */
  
  public function testReversal(string $referenceId)
  {
      
      $timestamp = $this->getMethod(__METHOD__)." ".date("Y-m-d H:i:s");
      $this->paymentObject->getRequest()->basketData($timestamp, 2.12, $this->currency, $this->secret);
  
      $this->paymentObject->reversal($referenceId);
  
      /* prepare request and send it to payment api */
      $request =  $this->paymentObject->getRequest()->prepareRequest();
      $response =  $this->paymentObject->getRequest()->send($this->paymentObject->getPaymentUrl(), $request);
      
      $this->assertTrue($response[1]->isSuccess(), 'Transaction failed : '.print_r($response[1]->getError(),1));
      $this->assertFalse($response[1]->isPending(), 'reversal is pending');
      $this->assertFalse($response[1]->isError(),'reversal failed : '.print_r($response[1]->getError(),1));
      
      return (string)$response[1]->getPaymentReferenceId();
  }
  
     
}