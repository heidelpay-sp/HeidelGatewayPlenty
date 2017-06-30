<?php

namespace Heidelpay\PhpApi\PaymentMethodes;
/**
 * This classe is the abstract payment method
 *
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 * @link  https://dev.heidelpay.de/PhpApi
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpApi
 * @category PhpApi
 */


abstract class AbstractPaymentMethod {
    
    /**
     * Payment Url of the live payment server
     * @var string url for heidelpay api connection real or live system
     */
    protected $_liveUrl 	   = 'https://heidelpay.hpcgw.net/ngw/post';
    
    /**
     * Payment Url of the sandbox payment server
     * @var string url for heidelpay api connection sandbox system
     */
	protected $_sandboxUrl     = 'https://test-heidelpay.hpcgw.net/ngw/post';
	
	/**
	 * HTTP Adapter for payment connection
	 * @var \Heidelpay\PhpApi/Adapter
	 */
	protected $_adapter = NULL;
	
	/**
	 * Heidelpay request object
	 * @var \Heidelpay\PhpApi\Request
	 */
	
	protected $_request = NULL;
	
	/**
	 * Heidelpay request array
	 * @var array request
	 */
	
	protected $_requestArray = NULL;
	
	/**
	 * Heidelpay response object
	 * @var \Heidelpay\PhpApi\Response
	 */
	protected $_response = NULL;
	
	/**
	 * Heidelpay response array
	 * @var array response
	 */
	
	protected $_responseArray = NULL;
	
	/**
	 * Payment code for this payment method
	 * @var string payment code
	 */
	
	protected $_paymentCode = NULL;
	
	/**
	 * Payment brand name for this payment method
	 * @var string brand name
	 */
	
	protected $_brand = NULL;
	
	/**
	 * Weather this Payment method can authorise transactions or not
	 * @var boolean canAuthorise
	 */
	
	protected $_canAuthorise = FALSE;
	
	/**
	 * Weather this Payment method can capture transactions or not
	 * @var boolean canCapture
	 */
	
	protected $_canCapture = FALSE;
	
	/**
	 * Weather this Payment method can debit transactions or not
	 * @var boolean canDebit
	 */
	
	protected $_canDebit = FALSE;
	
	/**
	 * Weather this Payment method can refund transactions or not
	 * @var boolean canRefund
	 */
	
    protected $_canRefund = FALSE;
    
    /**
     * Weather this Payment method can reversal transactions or not
     * @var boolean canReversal
     */
    
    protected $_canReversal = FALSE;
    
    /**
     * Weather this Payment method can rebill transactions or not
     * @var boolean canRebill
     */
    
    protected $_canRebill = FALSE;
    
    /**
     * Weather this Payment method can finalize transactions or not
     * 
     * Finalize will be used to tell the Heidelpay system that
     * the order has been shipped out.
     * @var boolean canFinalize
     */
    
    protected $_canFinalize = FALSE;
    
    /**
     * Weather this Payment method can register account data or not
     *
     * @var boolean canRegistration
     */
    
    protected $_canRegistration = FALSE;
    
    /**
     * Weather this Payment method can debit on registered account data or not
     *
     * @var boolean canDebitOnRegistration
     */
    
    protected $_canDebitOnRegistration = FALSE;
    
    /**
     * Weather this Payment method can authorize on registered account data or not
     *
     * @var boolean canAuthorizeOnRegistration
     */
    
    protected $_canAuthorizeOnRegistration = FALSE;
    
    /**
     * Dry run
     * 
     * If set to true request will be generated but not send to payment api.
     * This is use full for testing. 
     * 
     * @var boolean dry run
     */
    
    public  $_dryRun = FALSE;
    
    /**
     * Return the name of the used class
     * @return string class name
     */
    public static function getClassName() {
        return substr(strrchr(get_called_class(), '\\'), 1);
    }

	/**
	 * Set a new payment request object
	 * 
	 * @param \Heidelpay\PhpApi\Request $Request
	 */
	
	public function setRequest(\Heidelpay\PhpApi\Request $Request) {
	    $this->_request = $Request;
	}
	
	/**
	 * Get payment request object
	 * @return \Heidelpay\PhpApi\Request
	 */
	
	public function getRequest() {
	    if ($this->_request === NULL){
	       return $this->_request = new \Heidelpay\PhpApi\Request();
	    }
	    
	    return  $this->_request;
	}
	
	/**
	 * Get response object
	 * 
	 * @return \Heidelpay\PhpApi\Response
	 */
	
	public function getResponse()
	{
	    return $this->_response;
	}
	
	/**
	 * Set a HTTP Adapter for payment communication
	 * @param \Heidelpay\NgwPhpConnector\Adapter\$adapter
	 */
	
	public function setAdapter($adapter) {
	    $this->_adapter = $adapter;
	}
	
	/**
	 * Get HTTP Adapter for payment communication
	 * @return object
	 */
	
	public function getAdapter() {
	    return  $this->_adapter;
	}
	
	/**
	 * Get url of the used payment api
	 * @throws \Exception mode not set
	 * @return boolean|string url of the payment api
	 */
	
	public function getPaymentUrl() {
	    $mode = $this->getRequest()->getTransaction()->getMode();
	    
	    if( $mode === NULL) {
	        throw new \Exception('Transaction mode is not set');
	        return false;
	    } elseif ($mode == 'LIVE') {
	        return $this->_liveUrl;
	    } 
	        
	    return $this->_sandboxUrl;
	    
	}
	
	/**
	 * Payment type authorisation
	 * 
	 * Depending on the payment method this type normally means that the amount
	 * of the given account will only be authorized. In case of payment methods
	 * like Sofort and Giropay (so called online payments) this type will be 
	 * used just to get the redirect to their systems.
	 * 
	 * @return \Heidelpay\PhpApi\PaymentMethodes\AbstractPaymentMethod|boolean
	 */
	
	public function authorize(){
       
	    if ($this->_canAuthorise) {	    
	    $this->getRequest()->getPaymemt()->set('code', $this->_paymentCode.".PA");
	    $this->getRequest()->getCriterion()->set('payment_method', $this->getClassName());
	    if ($this->_brand !== NULL) $this->getRequest()->getAccount()->set('brand', $this->_brand);
	    
	    $uri = $this->getPaymentUrl();
	    $this->_requestArray = $this->getRequest()->prepareRequest();
	    
	    if ($this->_dryRun === FALSE and $uri !== NULL and is_array($this->_requestArray)) {
	          list($this->_responseArray, $this->_response) = $this->getRequest()->send($uri, $this->_requestArray, $this->getAdapter());
	    }
	    
	    return $this;
       }
       
       return false;
	}
	
	/**
	 * Payment type authorisation on registration
	 * 
	 * This payment type will be used to make an authorisation on a given registration.
	 *  
	 * @param string payment refernce id (uniqe id of the reqistration) 
	 * @return \Heidelpay\PhpApi\PaymentMethodes\AbstractPaymentMethod|boolean
	 */
	
	public function authorizeOnRegistration($PaymentRefernceId){
	
	    if ($this->_canAuthorizeOnRegistration) {
	        $this->getRequest()->getPaymemt()->set('code',$this->_paymentCode.".PA");
	        $this->getRequest()->getCriterion()->set('payment_method', $this->getClassName());
	        $this->getRequest()->getFrontend()->set('enabled','FALSE');
	        $this->getRequest()->getIdentification()->set('referenceId', $PaymentRefernceId);
	        $uri = $this->getPaymentUrl();
	        $this->_requestArray = $this->getRequest()->prepareRequest();
	
	        if ($this->_dryRun === FALSE and $uri !== NULL and is_array($this->_requestArray)) {
	            list($this->_responseArray, $this->_response) = $this->getRequest()->send($uri, $this->_requestArray, $this->getAdapter());
	        }
	
	        return $this;
	    }
	
	    return false;
	}
	
	/**
	 * Payment type capture
	 * 
	 * You can charge a given authorisation by capturing the transaction.
	 * 
	 * @param string payment refernce id ( uniqe id of the authorisation)
	 * @return \Heidelpay\PhpApi\PaymentMethodes\AbstractPaymentMethod|boolean
	 */
	
	public function capture($PaymentRefernceId){
       
	    if ($this->_canCapture) {	    
	    $this->getRequest()->getPaymemt()->set('code',$this->_paymentCode.".CP");
	    $this->getRequest()->getCriterion()->set('payment_method', $this->getClassName());
	    $this->getRequest()->getFrontend()->set('enabled','FALSE');
	    $this->getRequest()->getIdentification()->set('referenceId', $PaymentRefernceId);
	    if ($this->_brand !== NULL) $this->getRequest()->getAccount()->set('brand', $this->_brand);
	    
	           $uri = $this->getPaymentUrl();
	           $this->_requestArray = $this->getRequest()->prepareRequest();
	    
	           if ($this->_dryRun === FALSE and $uri !== NULL and is_array($this->_requestArray)) {
	                   list($this->_responseArray, $this->_response) = $this->getRequest()->send($uri, $this->_requestArray, $this->getAdapter());
	           }
	    
	    return $this;
       }
       
       return false;
	}
	
	/**
	 * Payment type debit
	 * 
	 * This payment type will charge the given account directly.
	 * 
	 * @return \Heidelpay\PhpApi\PaymentMethodes\AbstractPaymentMethod|boolean
	 */
	
	public function debit(){
	     
	    if ($this->_canDebit) {
	        $this->getRequest()->getPaymemt()->set('code',$this->_paymentCode.".DB");
	        $this->getRequest()->getCriterion()->set('payment_method', $this->getClassName());
	        if ($this->_brand !== NULL) $this->getRequest()->getAccount()->set('brand', $this->_brand);
	         
	           $uri = $this->getPaymentUrl();
	           $this->_requestArray = $this->getRequest()->prepareRequest();
	    
	           if ($this->_dryRun === FALSE and $uri !== NULL and is_array($this->_requestArray)) {
	                   list($this->_responseArray, $this->_response) = $this->getRequest()->send($uri, $this->_requestArray, $this->getAdapter());
	           }
	         
	        return $this;
	    }
	     
	    return false;
	}
	
	/**
	 * Payment type debit on registration
	 * 
	 * This payment type will charge the given account directly. The debit is
	 * related to a registration.
	 * 
	 * @param string payment refernce id ( uniqe id of the reqistration)
	 * @return \Heidelpay\PhpApi\PaymentMethodes\AbstractPaymentMethod|boolean
	 */
	
	public function debitOnRegistration($PaymentRefernceId){
	
	    if ($this->_canDebitOnRegistration) {
	        $this->getRequest()->getPaymemt()->set('code',$this->_paymentCode.".DB");
	        $this->getRequest()->getCriterion()->set('payment_method', $this->getClassName());
	        $this->getRequest()->getFrontend()->set('enabled','FALSE');
	        $this->getRequest()->getIdentification()->set('referenceId', $PaymentRefernceId);
	        $uri = $this->getPaymentUrl();
	        $this->_requestArray = $this->getRequest()->prepareRequest();
	         
	        if ($this->_dryRun === FALSE and $uri !== NULL and is_array($this->_requestArray)) {
	            list($this->_responseArray, $this->_response) = $this->getRequest()->send($uri, $this->_requestArray, $this->getAdapter());
	        }
	
	        return $this;
	    }
	
	    return false;
	}
	
	/**
	 * Payment type rebill
	 * 
	 * This payment type will be used to charge a given transaction again. For
	 * example, in case of a higher shipping cost. Please make sure that you
	 * have the permission of your customer to charge again.
	 * 
	 * @param string payment refernce id ( uniqe id of the debit or capture)
	 * @return \Heidelpay\PhpApi\PaymentMethodes\AbstractPaymentMethod|boolean
	 */
	
	public function rebill($PaymentRefernceId){
	
	    if ($this->_canRebill) {
	        $this->getRequest()->getPaymemt()->set('code',$this->_paymentCode.".RB");
	        $this->getRequest()->getCriterion()->set('payment_method', $this->getClassName());
	        $this->getRequest()->getFrontend()->set('enabled','FALSE');
	        $this->getRequest()->getIdentification()->set('referenceId', $PaymentRefernceId);
	        if ($this->_brand !== NULL) $this->getRequest()->getAccount()->set('brand', $this->_brand);
	        
	        $uri = $this->getPaymentUrl();
	        $this->_requestArray = $this->getRequest()->prepareRequest();
	
	        if ($this->_dryRun === FALSE and $uri !== NULL and is_array($this->_requestArray)) {
	            list($this->_responseArray, $this->_response) = $this->getRequest()->send($uri, $this->_requestArray, $this->getAdapter());
	        }
	
	        return $this;
	    }
	
	    return false;
	}
	
	/**
	 * Payment type refund
	 *
	 * This payment type will be used to give a charge amount or even parts of
	 * it back to the given account.
	 *
	 * @param string payment refernce id ( uniqe id of the debit or capture)
	 * @return \Heidelpay\PhpApi\PaymentMethodes\AbstractPaymentMethod|boolean
	 */
	
	public function refund($PaymentRefernceId){
	     
	    if ($this->_canRefund) {
	        $this->getRequest()->getPaymemt()->set('code',$this->_paymentCode.".RF");
	        $this->getRequest()->getCriterion()->set('payment_method', $this->getClassName());
	        $this->getRequest()->getFrontend()->set('enabled','FALSE');
	        $this->getRequest()->getIdentification()->set('referenceId', $PaymentRefernceId);
	        if ($this->_brand !== NULL) $this->getRequest()->getAccount()->set('brand', $this->_brand);
	         
	        $uri = $this->getPaymentUrl();
	        $this->_requestArray = $this->getRequest()->prepareRequest();
	         
	        if ($this->_dryRun === FALSE and $uri !== NULL and is_array($this->_requestArray)) {
	            list($this->_responseArray, $this->_response) = $this->getRequest()->send($uri, $this->_requestArray, $this->getAdapter());
	        }
	         
	        return $this;
	    }
	     
	    return false;
	}
	
	
	/**
	 * Payment type reversal
	 *
	 * This payment type will be used to give an uncharged amount or even parts of
	 * it back to the given account. This can be used to lower an amount on an
	 * invoice for example.
	 *
	 * @param string payment refernce id ( uniqe id of the authorisation)
	 * @return \Heidelpay\PhpApi\PaymentMethodes\AbstractPaymentMethod|boolean
	 */
	public function reversal($PaymentRefernceId){
	
	    if ($this->_canReversal) {
	        $this->getRequest()->getPaymemt()->set('code',$this->_paymentCode.".RV");
	        $this->getRequest()->getCriterion()->set('payment_method', $this->getClassName());
	        $this->getRequest()->getFrontend()->set('enabled','FALSE');
	        $this->getRequest()->getIdentification()->set('referenceId', $PaymentRefernceId);
	        if ($this->_brand !== NULL) $this->getRequest()->getAccount()->set('brand', $this->_brand);
	
	        $uri = $this->getPaymentUrl();
	        $this->_requestArray = $this->getRequest()->prepareRequest();
	
	        if ($this->_dryRun === FALSE and $uri !== NULL and is_array($this->_requestArray)) {
	            list($this->_responseArray, $this->_response) = $this->getRequest()->send($uri, $this->_requestArray, $this->getAdapter());
	        }
	
	        return $this;
	    }
	
	    return false;
	}
	
	/**
	 * Payment type registration
	 *
	 * This payment type will be used to save account data inside the heidelpay
	 * system. You will get back a payment reference id. This gives you a way
	 * to charge this account later or even to make a recurring payment.
	 *
	 *
	 * @return \Heidelpay\PhpApi\Paymentmethodes\AbstractPaymentMethod|boolean
	 */
	public function registration(){
	
	    if ($this->_canRegistration) {
	        $this->getRequest()->getPaymemt()->set('code',$this->_paymentCode.".RG");
	        $this->getRequest()->getCriterion()->set('payment_method', $this->getClassName());
	        if ($this->_brand !== NULL) $this->getRequest()->getAccount()->set('brand', $this->_brand);
	
	       $uri = $this->getPaymentUrl();
	       $this->_requestArray = $this->getRequest()->prepareRequest();
	    
    	   if ($this->_dryRun === FALSE and $uri !== NULL and is_array($this->_requestArray)) {
	                  list($this->_responseArray, $this->_response) = $this->getRequest()->send($uri, $this->_requestArray, $this->getAdapter());
	       }
	
	        return $this;
	    }
	
	    return false;
	}
}