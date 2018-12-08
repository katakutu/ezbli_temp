<?php
require_once ("api.connect.php");

$detail = inquiryKodeIuranByNIK($_POST[nik]);

if ($detail[daftarPeserta] == 'Y' && $detail[status] == '99') {
	header("location: daftar.php");
	exit();
} else 
if ($detail[status] == '99') {
	echo $detail[msg];
	exit();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<script src="//code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-3.0.0.min.js"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
</head>

<body>

inquiry By Nik : <?php echo $_POST[nik] ?>
<br /><br />

<table width="400px" border="0" bgcolor="#000000" cellpadding="10px" cellspacing="1px">
	<tr bgcolor="#FFFFFF">
        <td>Kode Iuran</td>
        <td>Status Bayar</td>
        <td>Status Bayar</td>
    </tr>
	<?php
	for($i=0; $i<sizeof($detail[data]); $i++) {
	?>
    <tr bgcolor="#FFFFFF">
        <td><?php echo $detail[data][$i][kodeIuran] ?></td>
        <td>
		<?php if ($detail[data][$i][statusBayar] == 'Y') { 
        	echo 'Bayar';
        } else { 
        	echo 'Belum Bayar';
        } ?> 
        </td>
        <td>
		<?php if ($detail[data][$i][statusBayar] == 'Y') { ?>
        	<input type="button" value="Cetak Ulang" onclick="location.href='cetakUlang.php?kodeIuran=<?php echo $detail[data][$i][kodeIuran] ?>'" />
        <?php } else { ?>
        	<input type="button" value="detail" onclick="location.href='inquiryKodeIuran.php?kodeIuran=<?php echo $detail[data][$i][kodeIuran] ?>'" />
        <?php } ?>        
        </td>
    </tr>
    <?php } ?>		
</table>
<br />
<?php if ($detail[pilihProgram] == 'Y') { ?>
	<input type="button" value="Pembayaran" onclick="location.href='pilihProgram.php?nik=<?php echo $_POST[nik] ?>'" />
<?php } ?>
</body>
</html>