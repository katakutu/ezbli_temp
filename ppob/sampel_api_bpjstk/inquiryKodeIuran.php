<?php
require_once ("api.connect.php");

$kodeIuran = inquiryKodeIuran($_GET[kodeIuran]);

$kp = explode(',', $kodeIuran[kodeProgram]);
foreach($kp as $key => $val) {
	if ($val == 'JHT') $progBPJSTK = '- Jaminan Hari Tua (JHT)<br/>';
	if ($val == 'JKK') $progBPJSTK .= '- Jaminan Kecelakaan Kerja (JKK)<br/>';
	if ($val == 'JKM') $progBPJSTK .= '- Jaminan Kematian (JKM)<br/>';
}

if ($kodeIuran[statusBayar] == 'Y') {
	die('Kode iuran sudah di bayar');
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>

<table width="400px" border="0">
  <tr>
    <td width="140px" style="padding:5px">Kode Iuran</td>
    <td width="20px" style="padding:5px">&nbsp;</td>
    <td style="padding:5px" align="right"><?php echo $kodeIuran[kodeIuran] ?></td>
  </tr>
  <tr>
    <td width="140px" style="padding:5px">Nama</td>
    <td width="20px" style="padding:5px">&nbsp;</td>
    <td style="padding:5px" align="right"><?php echo $kodeIuran[nama] ?></td>
  </tr>
  <tr>
    <td style="padding:5px">Program BPJSTK </td>
    <td style="padding:5px" colspan="2"><?php echo $progBPJSTK ?></td>
  </tr>
  <tr>
    <td style="padding:5px">Jumlah Pembayaran</td>
    <td style="padding:5px">Rp.</td>
    <td style="padding:5px" align="right"><?php echo $kodeIuran[jumlahBayar] ?></td>
  </tr>
  <tr>
    <td style="padding:5px">Biaya Admin</td>
    <td style="padding:5px">Rp.</td>
    <td style="padding:5px" align="right"><?php echo $kodeIuran[biayaAdmin] ?></td>
  </tr>
  <tr>
    <td style="padding:5px">Biaya Registrasi</td>
    <td style="padding:5px">Rp.</td>
    <td style="padding:5px" align="right"><?php echo $kodeIuran[biayaRegistrasi] ?></td>
  </tr>
  <tr>
    <td style="padding:5px">Total Pembayaran</td>
    <td style="padding:5px">Rp.</td>
    <td style="padding:5px" align="right"><?php echo $kodeIuran[totalPembayaran] ?></td>
  </tr>
</table>
<form action="bayarIuran.php" method="post">
<input type="hidden" name="kodeIuran" value="<?php echo $kodeIuran[kodeIuran] ?>" />
<button type="submit">Bayar</button>
</form>
</body>
</html>