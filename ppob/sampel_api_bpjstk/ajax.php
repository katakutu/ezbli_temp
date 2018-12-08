<?php
require_once ("api.connect.php");

if ($_POST['q'] == 'getKacabKabKota') {
	
	$i = getKabupaten($_POST[dat]);
	for($idx = 0; $idx<sizeof($i); $idx++) {
		$result .= $i[$idx][kode].'|'.$i[$idx][kabupaten];
		if ($idx<sizeof($i)-1) {
			$result .= ';';
		}
	}
	
	echo $result;
}

if ($_POST['q'] == 'getKacab') {
	
	$i = getKantorCabang($_POST[dat]);
	for($idx = 0; $idx<sizeof($i); $idx++) {
		$result .= $i[$idx][kode].'|'.$i[$idx][kantorCabang];
		if ($idx<sizeof($i)-1) {
			$result .= ';';
		}
	}
	
	echo $result;
}

if ($_POST['q'] == 'hitungIuran') {
	
	$hitIuran = array (
		'jmPenghasilan' => $_POST['jmPenghasilan'],
		'JHT' => $_POST['JHT'],
		'JKK' => 'Y',
		'JKM' => 'Y',
		'lokasiPekerjaan' => $_POST['lokasiPerkerjaan'],
		'periodeSelect' => $_POST['periodeSelect']
	);
	
	$i = hitungIuran($hitIuran);
	
	echo '00|'.$i[JHT].'|'.$i[JKK].'|'.$i[JKM].'|'.$i[BIAYA_TRANSAKSI].'|'.$i[BIAYA_REGISTRASI].'|'.$i[TOTAL];
	
}

?>