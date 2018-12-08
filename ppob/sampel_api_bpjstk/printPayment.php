<?php
error_reporting(0);
session_start();

$pData = $_SESSION[BPJS_PRINT_STRUK_DATA];

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
<title>Bukti Pembayaran Iuran BPJS Tenaga Kerja</title>

<style type="text/css">
body {
	font-family:Arial, Helvetica, sans-serif;
	font-size:14px;
	color:#000;
}

.canvas{
	margin:0 auto;
	width:600px;	
}

.titleA{
	font-size:16px;
	text-align:center;	
}
.titleB{
	font-size:14px;
	text-align:center;	
}

.subtitle{
	border:1px solid #7F7F7F;
	color:#000;
	font-size:15px;
	font-weight:bold;
	margin-bottom:15px;
	margin-top: 5px;
	padding:5px;
}

.note {
	font-style:italic;
	font-size:12px;	
}

.fillfeild {
	border-bottom:1px solid #7C7C7C;
}

.drawFillBox {
	border:1px solid #7F7F7F;
	margin-left: -1px;
	padding:10px;
}

.drawFwBox {
	border:1px solid #7F7F7F;
	margin-left: -1px;
	padding: 1px 2px;
}

</style>
<script src="//code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-3.0.0.min.js"></script>

</head>
<body>
<div class="canvas">
  
    <div class="titleA">STRUK PEMBAYARAN IURAN BPJSTK</div>
    
    <div style="margin-top:5px">
        <table width="100%" border="0">
          <tr>
            <td width="140px" >Kode Iuran</td>
            <td  align="right" colspan="3"><?php echo $pData[kodeIuran] ?></td>
          </tr>
          <tr>
            <td >No Registrasi</td>
            <td  align="right" colspan="3"><?php echo $pData[noRegistrasi] ?></td>
          </tr>
          <tr>
            <td >No Kepesertaan</td>
            <td  align="right" colspan="3"><?php echo $pData[noKepesertaan] ?></td>
          </tr>
          <tr>
            <td >Nama</td>
            <td  align="right" colspan="3"><?php echo $pData[nama] ?></td>
          </tr>
		  <?php if (in_array('JHT', $p)) {?>
          <tr>
            <td >Program BPJSTK </td>
            <td >- Jaminan Hari Tua (JHT)</td>
            <td  width="20px">Rp.</td>
            <td  align="right"><?php echo number_format($pData[JHTjmlIuran], 2, '.', ',') ?></td>
          </tr>
          <tr>
            <td >&nbsp;</td>
            <td >- Jaminan Kecelakaan Kerja (JKK)</td>
            <td  width="20px">Rp.</td>
            <td  align="right"><?php echo number_format($pData[JKKjmlIuran], 2, '.', ',') ?></td>
          </tr>
          <tr>
            <td >&nbsp;</td>
            <td >- Jaminan Kematian (JKM)</td>
            <td  width="20px">Rp.</td>
            <td  align="right"><?php echo number_format($pData[JKMjmlIuran], 2, '.', ',') ?></td>
          </tr>          
          <?php } else { ?>
          <tr>
            <td >Program BPJSTK</td>
            <td >- Jaminan Kecelakaan Kerja (JKK)</td>
            <td  width="20px">Rp.</td>
            <td  align="right"><?php echo number_format($pData[JKKjmlIuran], 2, '.', ',') ?></td>
          </tr>
          <tr>
            <td >&nbsp;</td>
            <td >- Jaminan Kematian (JKM)</td>
            <td  width="20px">Rp.</td>
            <td  align="right"><?php echo number_format($pData[JKMjmlIuran], 2, '.', ',') ?></td>
          </tr>           
          <?php } ?>
          <tr>
            <td  colspan="2">Jumlah Pembayaran</td>
            <td >Rp.</td>
            <td  align="right"><?php echo number_format($pData[jumlahBayar], 2, '.', ',') ?></td>
          </tr>
          <tr>
            <td  colspan="2">Biaya Admin</td>
            <td >Rp.</td>
            <td  align="right"><?php echo number_format($pData[biayaAdmin], 2, '.', ',') ?></td>
          </tr>
          <tr>
            <td  colspan="2">Biaya Registrasi</td>
            <td >Rp.</td>
            <td  align="right"><?php echo number_format($pData[biayaRegistrasi], 2, '.', ',') ?></td>
          </tr>
          <tr>
            <td  colspan="2">Total Pembayaran</td>
            <td >Rp.</td>
            <td  align="right"><?php echo number_format($pData[totalBayar], 2, '.', ',') ?></td>
          </tr>
          <tr>
            <td  colspan="3">Tanggal Efektif</td>
            <td  align="right"><?php echo $pData[tglEfektif] ?></td>
          </tr>
          <tr>
            <td  colspan="3">Tanggal Berakhir</td>
            <td  align="right" colspan="2"><?php echo $pData[tglExpired] ?></td>
          </tr>
          <tr>
            <td  colspan="3">Status Bayar</td>
            <td  align="right" colspan="2">
            <?php if ($pData[statusBayar] == 'Y') { 
					echo 'SUKSES';
			} else { 
				echo 'Belum Bayar';
			} ?> 
			</td>
          </tr>
          <tr>
            <td  colspan="5" style="padding-top:10px" align="center">Hubungi Call Center 500910 untuk layanan Claim</td>
          </tr>
          
        </table>
    </div>
</div>
<script>
$(document).ready(function() {
	window.focus(); window.print(); window.close();
});
</script>
</body>
</html>