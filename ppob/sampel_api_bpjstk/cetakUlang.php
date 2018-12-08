<?php
error_reporting(0);
session_start();

require_once ("api.connect.php");

$pData = inquiryCetakUlang($_GET[kodeIuran]);

$_SESSION[BPJS_PRINT_STRUK_DATA] = $pData;

$p = explode(',', $pData[program]);

foreach($p as $key => $val) {
	if ($val == 'JHT' && !$pData[JHTExist]) unset($p[$key]);
}


$i = explode(' ', $pData[tglAktif]); 
$pData[tglAktif] = $i[0];

$i = explode(' ', $pData[tglEfektif]); 
$pData[tglEfektif] = $i[0];

$i = explode(' ', $pData[tglExpired]); 
$pData[tglExpired] = $i[0];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
<div style="width:500px; margin:auto">
<br /><br />
<center>Struk BPJS Ketenagakerjaan Sektor Bukan Penerima Upah</center>
<br /><br />
<table width="100%" border="0">
  <tr>
    <td style="padding:5px">Kode Iuran</td>
    <td style="padding:5px" align="right" colspan="3"><?php echo $pData[kodeIuran] ?></td>
  </tr>
  <tr>
    <td style="padding:5px">No Registrasi</td>
    <td style="padding:5px" align="right" colspan="3"><?php echo $pData[noRegistrasi] ?></td>
  </tr>
  <tr>
    <td style="padding:5px">No Kepesertaan</td>
    <td style="padding:5px" align="right" colspan="3"><?php echo $pData[noKepesertaan] ?></td>
  </tr>
  <tr>
    <td style="padding:5px">Nama</td>
    <td style="padding:5px" align="right" colspan="3"><?php echo $pData[nama] ?></td>
  </tr>
  <tr>
    <td style="padding:5px" colspan="4">&nbsp;</td>
  </tr>
  <?php if (in_array('JHT', $p)) {?>
  <tr>
    <td style="padding:5px">Program BPJSTK </td>
    <td style="padding:5px">- Jaminan Hari Tua (JHT)</td>
    <td style="padding:5px" width="20px">Rp.</td>
    <td style="padding:5px" align="right"><?php echo number_format($pData[JHTjmlIuran], 2, '.', ',') ?></td>
  </tr>
  <tr>
    <td style="padding:5px">&nbsp;</td>
    <td style="padding:5px">- Jaminan Kecelakaan Kerja (JKK)</td>
    <td style="padding:5px" width="20px">Rp.</td>
    <td style="padding:5px" align="right"><?php echo number_format($pData[JKKjmlIuran], 2, '.', ',') ?></td>
  </tr>
  <tr>
    <td style="padding:5px">&nbsp;</td>
    <td style="padding:5px">- Jaminan Kematian (JKM)</td>
    <td style="padding:5px" width="20px">Rp.</td>
    <td style="padding:5px" align="right"><?php echo number_format($pData[JKMjmlIuran], 2, '.', ',') ?></td>
  </tr>          
  <?php } else { ?>
  <tr>
    <td style="padding:5px">Program BPJSTK</td>
    <td style="padding:5px">- Jaminan Kecelakaan Kerja (JKK)</td>
    <td style="padding:5px" width="20px">Rp.</td>
    <td style="padding:5px" align="right"><?php echo number_format($pData[JKKjmlIuran], 2, '.', ',') ?></td>
  </tr>
  <tr>
    <td style="padding:5px">&nbsp;</td>
    <td style="padding:5px">- Jaminan Kematian (JKM)</td>
    <td style="padding:5px" width="20px">Rp.</td>
    <td style="padding:5px" align="right"><?php echo number_format($pData[JKMjmlIuran], 2, '.', ',') ?></td>
  </tr>           
  <?php } ?>
  <tr>
    <td style="padding:5px" colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td style="padding:5px" colspan="2">Jumlah Pembayaran</td>
    <td style="padding:5px" width="20px">Rp.</td>
    <td style="padding:5px" align="right"><?php echo number_format($pData[jumlahBayar], 2, '.', ',') ?></td>
  </tr>
  <tr>
    <td style="padding:5px" colspan="2">Biaya Admin</td>
    <td style="padding:5px">Rp.</td>
    <td style="padding:5px" align="right"><?php echo number_format($pData[biayaAdmin], 2, '.', ',') ?></td>
  </tr>
  <tr>
    <td style="padding:5px" colspan="2">Biaya Registrasi</td>
    <td style="padding:5px">Rp.</td>
    <td style="padding:5px" align="right"><?php echo number_format($pData[biayaRegistrasi], 2, '.', ',') ?></td>
  </tr>
  <tr>
    <td style="padding:5px" colspan="2">Total Pembayaran</td>
    <td style="padding:5px">Rp.</td>
    <td style="padding:5px" align="right"><?php echo number_format($pData[totalBayar], 2, '.', ',') ?></td>
  </tr>
  <tr>
    <td style="padding:5px" colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td style="padding:5px">Tanggal Efektif</td>
    <td style="padding:5px" align="right" colspan="3"><?php echo $pData[tglEfektif] ?></td>
  </tr>
  <tr>
    <td style="padding:5px">Tanggal Berakhir</td>
    <td style="padding:5px" align="right" colspan="3"><?php echo $pData[tglExpired] ?></td>
  </tr>
  <tr>
    <td style="padding:5px">Status Bayar</td>
    <td style="padding:5px" align="right" colspan="3">
    <?php if ($pData[statusBayar] == 'Y') { 
        	echo 'SUKSES';
	} else { 
		echo 'Belum Bayar';
	} ?>    
    </td>
  </tr>
</table>

<button type="button" class="redButton" onclick="cetakBuktiBayar()">Cetak Bukti Pembayaran</button>
</div>

<script>
function cetakBuktiBayar() {
	window.open("printPayment.php","Cetak Formulir","width=900,height=500");
}
	
</script>
        
</body>
</html>