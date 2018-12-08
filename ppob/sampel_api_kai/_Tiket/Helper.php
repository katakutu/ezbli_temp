<?php

require_once (__DIR__ . "/../../lib/nusoap.php");

class ApiHelperKAI
{
	const BBUZ_PP_HOST	= 'http://117.102.64.238:1212/index.php?wsdl';
	const SOAP_TIMEOUT	= 60;
	const SOAP_RESPONSE_TIMEOUT = 180;

	// key and secret for development
	private $apiKey		= 'bd8446eaa574ab00ab72249f8b06a759';	// api key
	private $secretKey	= 'a3355920444fae8eb84b1f6304bbe1d9';	// secret key
	private $soap;

	public function __construct()
	{
		$this->soap = new nusoap_client(
			self::BBUZ_PP_HOST,
			$wsdl = true,
			$proxyhost = false,
			$proxyport = false,
			$proxyusername = false,
			$proxypassword = false,
			$timeout = self::SOAP_TIMEOUT,
			$response_timeout = self::SOAP_RESPONSE_TIMEOUT,
			$portName = ''
		);

		$this->soap->setCredentials($this->apiKey, $this->secretKey, 'basic');
	}

	public function info($data = [])
	{
		return $this->soap->call('mitraInfo', $data);
	}

	public function kai_station($data = [])
	{
		return $this->soap->call('kaiGetStationList', $data);
	}

	public function kai_search($data = [])
	{
		$parts = [
			'org' => $data['asal'],
			'des' => $data['tujuan'],
			'date' => $data['tanggal']
		];

		return $this->soap->call('kaiSearch', $parts);
	}

	public function kai_seatmap($data = [])
	{
		$parts = [
			'org' => $data['asal'],
			'des' => $data['tujuan'],
			'date' => $data['tanggal'],
			'trainNo' => $data['no_kereta']
		];

		return $this->soap->call('kaiGetSeatMap', $parts);
	}

	public function kai_seatmap_subclass($data = [])
	{
		$parts = [
			'org' => $data['asal'],
			'des' => $data['tujuan'],
			'date' => $data['tanggal'],
			'trainNo' => $data['no_kereta'],
			'subClass' => $data['subclass']
		];

		return $this->soap->call('kaiGetSeatMapSubClass', $parts);
	}

	public function kai_book($data = [])
	{
		$parts = [
			'org' => $data['asal'],
			'des' => $data['tujuan'],
			'date' => $data['tanggal'],
			'trainNo' => $data['no_kereta'],
			'subClass' => $data['subclass'],
			'adult' => $data['dewasa'],
			'child' => NULL,
			'infant' => $data['bayi'],
			'travellerArray' => $data['penumpang'],
			'seatSelect' => $data['pilih_kursi'],
			'wagonCode' => $data['kode_gerbong'],
			'wagonNumber' => $data['no_gerbong'],
			'seats' => $data['kursi']
		];

		return $this->soap->call('kaiBook', $parts);
	}

	public function kai_check_book($data = [])
	{
		$parts = [
			'bookingCode' => $data['kode_booking']
		];

		return $this->soap->call('kaiCheckBook', $parts);
	}

	public function kai_issue($data = [])
	{
		$parts = [
			'bookingCode' => $data['kode_booking'],
			'totalPrice' => $data['harga']
		];

		return $this->soap->call('kaiIssued', $parts);
	}
}
