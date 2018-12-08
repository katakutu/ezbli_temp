<?php

ini_set('max_execution_time', 600);
ini_set('display_errors', false);

require_once (__DIR__ . "/../lib/nusoap.php");

class ApiHelperTravel {

	const BBUZ_HOST	= 'http://117.102.64.238:1212/index.php?wsdl';
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

	public function travelGetAgen()
	{
		return $this->soap->call('travelGetAgen', array());
	}

	public function travelGetKeberangkatan($kodeAgen)
	{
		$params = array(
			'kodeAgen'			=> $kodeAgen
		);

		return $this->soap->call('travelGetKeberangkatan', $params);
	}

	public function travelGetKedatangan($kodeAgen, $idKeberangkatan)
	{
		$params = array(
			'kodeAgen'			=> $kodeAgen,
			'idKeberangkatan'	=> $idKeberangkatan
		);

		return $this->soap->call('travelGetKedatangan', $params);
	}

	public function travelGetJadwalKeberangkatan($kodeAgen, $idKeberangkatan, $idKedatangan, $tanggal, $penumpang)
	{
		$params = array(
			'kodeAgen'			=> $kodeAgen,
			'idKeberangkatan'	=> $idKeberangkatan,
			'idKedatangan'		=> $idKedatangan,
			'tanggal'			=> $tanggal,
			'penumpang'			=> $penumpang
		);

		return $this->soap->call('travelGetJadwalKeberangkatan', $params);
	}

	public function travelGetMapKursi($kodeAgen, $kodeJadwal, $tanggal, $kodeLayoutKursi, $idJadwal)
	{
		$params = array(
			'kodeAgen'			=> $kodeAgen,
			'kodeJadwal'		=> $kodeJadwal,
			'tanggal'			=> $tanggal,
			'kodeLayoutKursi'	=> $kodeLayoutKursi,
			'idJadwal'			=> $idJadwal
		);

		return $this->soap->call('travelGetMapKursi', $params);
	}

	public function travelBook($kodeAgen, $kodeJadwal, $tanggal, $namaPemesan, $alamatPemesan, $telpPemesan, $emailPemesan, $jumlahPenumpang, $noKursi, $namaPenumpang)
	{
		$params = array(
			'kodeAgen'			=> $kodeAgen,
			'kodeJadwal'		=> $kodeJadwal,
			'tanggal'			=> $tanggal,
			'namaPemesan'		=> $namaPemesan,
			'alamatPemesan'		=> $alamatPemesan,
			'telpPemesan'		=> $telpPemesan,
			'emailPemesan'		=> $emailPemesan,
			'jumlahPenumpang'	=> $jumlahPenumpang,
			'noKursi'			=> $noKursi,
			'namaPenumpang'		=> $namaPenumpang,
			'kodePromo'			=> $kodePromo,
			'tipePromo'			=> $tipePromo,
			'kuota_id'			=> $kuota_id,
			'id_layout'			=> $id_layout
		);

		return $this->soap->call('travelBook', $params);
	}

	public function travelCekReservasi($kodeBooking)
	{
		$params = array(
			'kodeBooking'		=> $kodeBooking
		);

		return $this->soap->call('travelCekReservasi', $params);
	}

	function travelPayBook($kodeBooking, $totalHarga)
	{
		$params = array(
			'kodeBooking'		=> $kodeBooking,
			'totalHarga'		=> $totalHarga
		);

		return $this->soap->call('travelPayBook', $params);
	}

}
