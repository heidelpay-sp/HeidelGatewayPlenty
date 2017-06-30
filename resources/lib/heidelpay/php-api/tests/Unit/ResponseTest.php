<?php
namespace Heidelpay\Tests\PhpApi\Unit;
use PHPUnit\Framework\TestCase;
use \Heidelpay\PhpApi\Response;

/**
 * 
 *  This unit test will cover the response object.   
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

class ResponseTest extends TestCase
{
    /**
     * @var \Heidelpay\PhpApi\Response
     */
    
    protected $_resposneObject = NULL;
    
    /**
     * setUp sample response Object
     * 
     * {@inheritDoc}
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    
    public function setUp() {
        
        $responseSample = array(
            'NAME_FAMILY' => 'Berger-Payment',
            'IDENTIFICATION_TRANSACTIONID' => '2843294932',
            'ADDRESS_COUNTRY' => 'DE',
            'ADDRESS_STREET' => 'Vagerowstr. 18',
            'FRONTEND_ENABLED' => TRUE,
            'PRESENTATION_AMOUNT' => 23.12,
            'TRANSACTION_MODE' => 'CONNECTOR_TEST',
            'ACCOUNT_EXPIRY_MONTH' => '05',
            'PROCESSING_TIMESTAMP' => '2016-09-16 12:14:31',
            'CONTACT_EMAIL' => 'development@heidelpay.de',
            'FRONTEND_RESPONSE_URL' => 'http://dev.heidelpay.de/response.php',
            'REQUEST_VERSION' => 1.0,
            'ACCOUNT_BRAND' => 'MASTER',
            'PROCESSING_STATUS_CODE' => '90',
            'NAME_GIVEN' => 'Heidel',
            'FRONTEND_PAYMENT_FRAME_ORIGIN' => 'http://dev.heidelpay.de/',
            'IDENTIFICATION_SHORTID' => '3379.5447.1520',
            'ADDRESS_CITY' => 'Heidelberg',
            'ACCOUNT_HOLDER' => 'Heidel Berger-Payment',
            'PROCESSING_CONFIRMATION_STATUS' => 'CONFIRMED',
            'PROCESSING_CODE' => 'CC.RG.90.00',
            'PROCESSING_STATUS' => 'NEW',
            'SECURITY_SENDER' => '31HA07BC8142C5A171745D00AD63D182',
            'USER_LOGIN' => '31ha07bc8142c5a171744e5aef11ffd3',
            'USER_PWD' => '93167DE7',
            'IDENTIFICATION_SHOPPERID' => '12344',
            'PROCESSING_RETURN_CODE' => '000.100.112',
            'PROCESSING_RESULT' => 'ACK',
            'FRONTEND_MODE' => 'WHITELABEL',
            'IDENTIFICATION_UNIQUEID' => '31HA07BC8108A9126F199F2784552637',
            'CRITERION_SECRET' => '209022666cd4706e5f451067592b6be1aff4a913d5bb7f8249f7418ee25c91b318ebac66f41a6692539c8923adfdad6aae26138b1b3a7e37a197ab952be57876',
            'ACCOUNT_EXPIRY_YEAR' => '2018',
            'PRESENTATION_CURRENCY' => 'EUR',
            'PROCESSING_REASON_CODE' => '00',
            'ACCOUNT_VERIFICATION' => '***',
            'ADDRESS_STATE' => 'DE-BW',
            'ADDRESS_ZIP' => '69115',
            'ACCOUNT_NUMBER' => '545301******9543',
            'FRONTEND_PREVENT_ASYNC_REDIRECT' => FALSE,
            'PROCESSING_REASON' => 'SUCCESSFULL',
            'PROCESSING_RETURN' => "Request successfully processed in 'Merchant in Connector Test Mode'",
            'TRANSACTION_CHANNEL' => '31HA07BC8142C5A171744F3D6D155865',
            'FRONTEND_LANGUAGE' => 'DE',
            'PAYMENT_CODE' => 'CC.RG'
            );
        
            $this->_resposneObject = new Response($responseSample);
        
    }
    
   /**
    * function test for isSuccess method
    * @group integrationTest
    */
  public function testIsSuccess()
  {
    $this->assertTrue($this->_resposneObject->isSuccess(), 'isSuccess should be true');
    $this->_resposneObject->getProcessing()->set('result', 'NOK');
    $this->assertFalse($this->_resposneObject->isSuccess(), 'isSuccess should be false.');
  }
  
  /**
   * function test for isPending method
   * @group integrationTest
   */
  public function testIsPending()
  {
      $this->assertFalse($this->_resposneObject->isPending(), 'isPending should be false');
      $this->_resposneObject->getProcessing()->set('status_code', '80');
      $this->assertTrue($this->_resposneObject->isPending(), 'isPending should be true');
  }
  
  /**
   * function test for isError method
   * @group integrationTest
   */
  public function testIsError()
  {
      $this->assertFalse($this->_resposneObject->isError(), 'isError should be false');
      $this->_resposneObject->getProcessing()->set('result', 'NOK');
      $this->assertTrue($this->_resposneObject->isError(), 'isError should be true');
      
  }
  
  /**
   * function test for getError method
   * @group integrationTest
   */
  public function testGetError()
  {
      $expectedError = array(
            'code' => '000.100.112',
            'message' => "Request successfully processed in 'Merchant in Connector Test Mode'"
       );
  
   $this->assertEquals($expectedError ,$this->_resposneObject->getError());   
  }
  
  /**
   * function test for getPaymentReferenceID method
   * @group integrationTest
   */
  public function testGetPaymentReferenceId()
  {
      $this->assertEquals('31HA07BC8108A9126F199F2784552637', $this->_resposneObject->getPaymentReferenceId());
  }
  
  /**
   * function test for getPaymentFromUrl methode
   * @group integrationTest
   */
  
  public function testGetPaymentFromUrl()
  {
           
      /** iframe url for credit and debit card*/
      $expectedUrl = 'http://dev.heidelpay.de';
      $this->_resposneObject->getFrontend()->set('payment_frame_url', $expectedUrl);
      $this->assertEquals($expectedUrl, $this->_resposneObject->getPaymentFromUrl());
      
      
      $expectedUrl = 'http://www.heidelpay.de';
      $this->_resposneObject->getFrontend()->set('redirect_url', $expectedUrl);
      
      /** url in case of credit and debit card refernce Transaction */
      $this->_resposneObject->getIdentification()->set('referenceid', '31HA07BC8108A9126F199F2784552637');
      $this->assertEquals($expectedUrl, $this->_resposneObject->getPaymentFromUrl());
      
      /** unset reference id */
      $this->_resposneObject->getIdentification()->set('referenceid', NULL);
       
      
      /** url for non credit or debit card transactions */
      
      $this->_resposneObject->getPaymemt()->set('code', 'OT.PA');
      $this->_resposneObject->getFrontend()->set('redirect_url', $expectedUrl);
      $this->assertEquals($expectedUrl, $this->_resposneObject->getPaymentFromUrl());
  }
  
  /**
   * function test for verifySecurityHash
   * @group integrationTest
   */
   
}