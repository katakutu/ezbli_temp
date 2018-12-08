<?php

require_once (__DIR__ . "/../lib/nusoap.php");

class ApiHelperPLN
{
	const BBUZ_PP_HOST	= 'http://117.102.64.238:1212/pp/index.php?wsdl';
	const SOAP_TIMEOUT	= 60;
	const SOAP_RESPONSE_TIMEOUT = 360;

	// key and secret for development
	private $apiKey		= 'bd8446eaa574ab00ab72249f8b06a759';	// api key
	private $secretKey	= 'a3355920444fae8eb84b1f6304bbe1d9';	// secret key
	private $soap;

	public function __construct()
	{
		$this->soap = new nusoap_client(
			self::BBUZ_PP_HOST,
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

	public function inquiry($productCode, $idPel, $denom, $miscData= '')
	{
		$params = array(
			'productCode' => $productCode,
			'idPel'		  => $idPel,
			'idPel2'	  => $denom,
			'miscData'	  => $miscData
		);

		return $this->soap->call('ppInquiry', $params);
	}

	public function payment($productCode, $refID, $nominal, $miscData= '')
	{
		$params = array(
			'productCode' => $productCode,
			'refID'		  => $refID,
			'nominal'	  => $nominal,
			'miscData'	  => $miscData
		);

		return $this->soap->call('ppPayment', $params);
	}

	public function mitraInfo()
	{
		return $this->soap->call('ppMitraInfo', array());
	}

	public function options($cmd, $data) {
		$params = array(
			'cmd' => $cmd,
			'data' => $data
		);

		return $this->soap->call('ppOptions', $params);
	}
}
