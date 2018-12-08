<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once (__DIR__ . "/../lib/nusoap.php");

class ApiHelperVoucherGame {

	const BBUZ_HOST	= 'http://117.102.64.238:1212/voucherGame.php?wsdl';
	const SOAP_TIMEOUT	= 60;
	const SOAP_RESPONSE_TIMEOUT = 360;

	private $apiKey		= 'bd8446eaa574ab00ab72249f8b06a759';	// api key
	private $secretKey	= 'a3355920444fae8eb84b1f6304bbe1d9';	// secret key

	private $soap;

	public function __construct()
	{
		$this->soap = new nusoap_client(
			self::BBUZ_HOST,
			true,
			false,
			false,
			false,
			false,
			self::SOAP_TIMEOUT,
			self::SOAP_RESPONSE_TIMEOUT,
			''
		);

		$this->soap->setCredentials($this->apiKey, $this->secretKey, 'basic');
	}

	public function mitraInfo() {
		return $this->soap->call('mitraInfo', array());
	}

	public function productListGame($productID='') {
		$params = array ('productID' => $productID);
		return $this->soap->call('productListGame', $params);
	}

	public function paymentVoucherGame($productID, $amount, $uniqueID) {
		$params = array ('productID' => $productID, 'amount' => $amount, 'uniqueID' => $uniqueID);
		return $this->soap->call('paymentVoucherGame', $params);
	}

	public function voucherGameStatus($msisdn, $trxID) {
		$params = array ('msisdn' => $msisdn, 'trxID' => $trxID);
		return $this->soap->call('voucherGameStatus', $params);
	}

}
?>
