<?php
//ini_set('max_execution_time', 120);
//ini_set('display_errors', true);
ini_set('error_reporting', E_ALL^E_NOTICE);

require_once (__DIR__ . "/../lib/nusoap.php");

class ApiHelperBPJSTK
{
	const HOST	= 'http://117.20.55.221:2012/index.php?wsdl';
	const SOAP_TIMEOUT	= 120;
	const SOAP_RESPONSE_TIMEOUT = 200;

	// key and secret for development
	private $apiKey		= 'bd8446eaa574ab00ab72249f8b06a759';	// api key
	private $secretKey	= 'a3355920444fae8eb84b1f6304bbe1d9';	// secret key
	private $soap;

	public function __construct()
	{
		$this->soap = new nusoap_client(
			self::HOST,
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

	public function helloTest($data) {
		return $this->soap->call('Hello', array('data' => $data));
	}

	public function getLokasiKerja() {
		return $this->soap->call('getLokasiKerja', array ());
	}

	public function getProvinsiList() {
		return $this->soap->call('getProvinsiList', array());
	}

	public function getKabupaten($provKode) {
		$params = array('kodeProvinsi' => $provKode);
		return $this->soap->call('getKabupaten', $params);
	}

	public function getKantorCabang($kabCode) {
		$params = array('kodeKabupaten' => $kabCode);
		return $this->soap->call('getKantorCabang', $params);
	}

	public function verifyKTPeL($data) {
		$params = array(
			'nik' => $data['nik'],
			'namaKtp' => $data['namaKtp'],
			'tgLahir' => $data['tgLahir'],
			'noPonsel' => $data['noPonsel'],

		);

		return $this->soap->call('verifyKTPeL', array('data' => $params));
	}

	public function registration($data) {
		$params = array(
			'nik' => $data['nik'],
			'namaKtp' => $data['namaKtp'],
			'expNik' => $data['expNik'],
			'tempatLahir' => $data['tempatLahir'],
			'tgLahir' => $data['tgLahir'],
			'kotaDomisili' => $data['kotaDomisili'],
			'alamat' => $data['alamat'],
			'kecamatan' => $data['kecamatan'],
			'kelurahan' => $data['kelurahan'],
			'kodepos' => $data['kodepos'],
			'noPonsel' => $data['noPonsel'],
			'email' => $data['email'],
			'JHT' => $data['JHT'],
			'JKK' => $data['JKK'],
			'JKM' => $data['JKM'],
			'periodeSelect' => $data['periodeSelect'],
			'jmPenghasilan' => $data['jmPenghasilan'],
			'lokasiPekerjaan' => $data['lokasiPekerjaan'],
			'jenisPekerjaan' => $data['jenisPekerjaan'],
			'jkStart' => $data['jkStart'],
			'jkStop' => $data['jkStop'],
			'notifySMS' => $data['notifySMS'],
			'kodeProvKantor' => $data['kodeProvKantor'],
			'kodeKabKantor' => $data['kodeKabKantor'],
			'kodeKantorCab' => $data['kodeKantorCab']
		);

		return $this->soap->call('registration', array('data' => $params));
	}


	public function bayarIuran($kodeIuran) {
		return $this->soap->call('bayarIuran', array('kodeIuran' => $kodeIuran));
	}

	public function inquiryKodeIuranByNIK($nik) {
		return $this->soap->call('inquiryKodeIuranByNIK', array('nik' => $nik));
	}

	public function hitungIuran($data) {
		$params = array(
			'jmPenghasilan' => $data['jmPenghasilan'],
			'JHT' => $data['JHT'],
			'JKK' => $data['JKK'],
			'JKM' => $data['JKM'],
			'lokasiPekerjaan' => $data['lokasiPekerjaan'],
			'periodeSelect' => $data['periodeSelect']
		 );

		return $this->soap->call('hitungIuran', array('data' => $params));
	}

	public function inquiryKodeIuran($kodeIuran) {
		return $this->soap->call('inquiryKodeIuran', array('kodeIuran' => $kodeIuran));

	}

	public function inquiryCetakUlang($kodeIuran) {
		return $this->soap->call('inquiryCetakUlang', array('kodeIuran' => $kodeIuran));

	}

	public function getDataPeserta($nik) {
		return $this->soap->call('getDataPeserta', array('nik' => $nik));

	}

	public function pilihProgram($data) {
		$params = array(
			'nik' => $data['nik'],
			'JHT' => $data['JHT'],
			'JKK' => $data['JKK'],
			'JKM' => $data['JKM'],
			'periodeSelect' => $data['periodeSelect'],
			'jmPenghasilan' => $data['jmPenghasilan'],
			'lokasiPekerjaan' => $data['lokasiPekerjaan'],
			'jenisPekerjaan' => $data['jenisPekerjaan'],
			'jkStart' => $data['jkStart'],
			'jkStop' => $data['jkStop']
		);

		return $this->soap->call('pilihProgram', array('data' => $params));
	}
}

?>
