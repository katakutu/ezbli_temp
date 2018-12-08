<?php
require_once ("api.connect.php");
$detail = getDataPeserta($_GET[nik]);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui.css" media="screen" />

<script src="//code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-3.0.0.min.js"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<title>Untitled Document</title>
</head>

<body>
<form name="pilihProgram" action="pilihProgramSubmit.php" method="post" id="reselectpkg">
<input type="hidden" name="tglLahir" value="<?php echo $detail[tglLahir] ?>"  />
<input type="hidden" name="noPonsel" value="<?php echo $detail[noHp] ?>"  />
<input type="hidden" name="kodeKantorCab" value="<?php echo $detail[kodeKantor] ?>"  />
<input type="hidden" name="email" value="<?php echo $detail[email] ?>"  />

<table class="formGridTable" width="100%" border="0">
  <tr>
    <td width="150px">No Kepesertaan</td>
    <td><input  type="text" name="nik" value="<?php echo $detail[noKepesertaan] ?>" readonly="readonly" style="width:200px" /></td>
  </tr>
  <tr>
    <td>No Registrasi</td>
    <td><input  type="text" name="nreg" value="<?php echo $detail[noRegistrasi] ?>" readonly="readonly" style="width:200px" /></td>
  </tr>
  <tr>
    <td>Nama Lengkap</td>
    <td><input  type="text" name="namaLengkap" value="<?php echo $detail[namaLengkap] ?>" readonly="readonly" style="width:300px" /></td>
  </tr>
  <tr>
    <td>Alamat</td>
    <td><textarea  name="alamat" cols="40" rows="3" readonly="readonly"><?php echo $detail[alamat] ?></textarea></td>
  </tr>
  <tr>
    <td>Pilih Program</td>
    <td>
        <label for="g" style="margin-right:20px"><input id="g" type="checkbox" name="JHT" value="Y" onchange="jhtClick(this)" />Jaminan Hari Tua <strong style="color:#8FD32A">(JHT)</strong></label><br />
        <?php if (!empty($detail[JHTtglAktif])) { ?>
        <div class="masaBerlaku" id="masaBJHT">Berlaku dari <?php echo $detail[JHTtglAktif] ?> s/d <?php echo $detail[JHTtglExpired] ?></div>
        <?php } ?>
        <label for="h" style="margin-right:20px"><input id="h" type="checkbox" name="JKK" value="Y" checked="checked" onchange="this.checked = true;" />Jaminan Kecelakaan Kerja <strong style="color:#8FD32A">(JKK)</strong></label>
        <div class="masaBerlaku" id="masaBJKK">Berlaku dari <?php echo $detail[JKKtglAktif] ?> s/d <?php echo $detail[JKKtglExpired] ?></div>
        <label for="i" style="margin-right:20px"><input id="i" type="checkbox" name="JKM" value="Y" checked="checked" onchange="this.checked = true;" />Jaminan Kematian <strong style="color:#8FD32A">(JKM)</strong></label>
        <div class="masaBerlaku" id="masaBJKM">Berlaku dari <?php echo $detail[JKMtglAktif] ?> s/d <?php echo $detail[JKMtglExpired] ?></div>
    </td>
  </tr>
  <tr>
    <td>Periode Program</td>
    <td>
    <select name="periodeSelect" >
        <option value="">Pilih Periode Program</option>
        <option value="1">1 Bulan</option>
        <option value="3">3 Bulan</option>
        <option value="6">6 Bulan</option>
        <option value="9">9 Bulan</option>
        <option value="12">12 Bulan</option>
    </select>
    </td>
  </tr>
  <tr>
    <td>Jumlah Penghasilan</td>
    <td><input  type="text" name="jmPenghasilan" value="<?php echo $detail[upahTerakhir] ?>"  autocomplete="off" onkeyup="format_harga(this)" onKeyPress="return numbersonly(this, event)" /></td>
  </tr>
  <tr>
    <td>Lokasi Pekerjaan</td>
    <td><select  name="lokasiPekerjaan">
        <option value="">Pilih Lokasi Pekerjaan</option>
        <?php
        $i = getLokasiKerja('');        
        for($idx = 0; $idx<sizeof($i); $idx++) {
        ?>
        <option value="<?php echo $i[$idx][kode] ?>"><?php echo $i[$idx][nama] ?></option>
        <?php } ?>
    </select>
    
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
        <table width="250px" border="0" cellspacing="1px" cellpadding="0px" bgcolor="#666666">
          <tr bgcolor="#04ED33">
            <td align="center" class="hitIuranTD"><strong>Biaya</strong></td>
            <td align="center" class="hitIuranTD"><strong>Total</strong></td>
          </tr>
          <tr bgcolor="#FFFFFF">
            <td align="left" class="hitIuranTD">Program JHT</td>
            <td align="right" class="hitIuranTD"><div id="JHTTotal">0</div></td>
          </tr>
          <tr bgcolor="#FFFFFF">
            <td align="left" class="hitIuranTD">Program JKK</td>
            <td align="right" class="hitIuranTD"><div id="JKKTotal">0</div></td>
          </tr>
          <tr bgcolor="#FFFFFF">
            <td align="left" class="hitIuranTD">Program JKM</td>
            <td align="right" class="hitIuranTD"><div id="JKMTotal">0</div></td>
          </tr>
          <tr bgcolor="#E5E5E5">
            <td align="left" class="hitIuranTD">Admin Loket</td>
            <td align="right" class="hitIuranTD"><div id="AdminLoket">0</div></td>
          </tr>
          <tr bgcolor="#E5E5E5">
            <td align="left" class="hitIuranTD">Registrasi</td>
            <td align="right" class="hitIuranTD"><div id="Registrasi">0</div></td>
          </tr>
          <tr bgcolor="#00CCFF">
            <td align="left" class="hitIuranTD">Total Bayar</td>
            <td align="right" class="hitIuranTD"><div id="TotalBayar">0</div></td>
          </tr>
        </table>
        <br />
        <button type="button" onclick="hitungIuran()">Hitung Iuran</button><span id="loading"></span>
</td>
  </tr>
  <tr>
    <td>Jenis Pekerjaan</td>
    <td><input  type="text" name="jenisPekerjaan" value="<?php echo $detail[jenisPekerjaan] ?>" autocomplete="off" />
        <br />(Maks 3 pekerjaan contoh: Tukang, Pedagang, Petani)
    </td>
  </tr>
  <tr>
    <td>Awal Jam Kerja</td>
    <td><input type="text" name="jkStart" id="jkStartSpinner" value="00:00" /></td>
  </tr>
  <tr>
    <td>Akhir Jam Kerja</td>
    <td><input type="text" name="jkStop" id="jkStopSpinner" value="00:00" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><button type="submit">Lanjutkan</button></td>
  </tr>          
</table>
</form>

<script>

var JHTClick = 'N';
function jhtClick(obj) {
	if (obj.checked == true) {
		JHTClick = 'Y';
	} else {
		JHTClick = 'N';
	}
}

function hitungIuran() {
	$('#loading').html('loading...');
	$.ajax({
		type: 'POST',
		url: 'ajax.php',
		data: 'q=hitungIuran&jmPenghasilan=' + document.pilihProgram.jmPenghasilan.value + '&JHT=' + JHTClick + '&lokasiPerkerjaan=' + document.pilihProgram.lokasiPekerjaan.value + '&periodeSelect=' + document.pilihProgram.periodeSelect.value ,
		success: function(data) {
			i = data.split('|');
			if (i[0] == '00') {
				$('#JHTTotal').html(i[1]);
				$('#JKKTotal').html(i[2]);
				$('#JKMTotal').html(i[3]);
				$('#AdminLoket').html(i[4]);
				$('#Registrasi').html(i[5]);
				$('#TotalBayar').html(i[6]);
			} else {
				alert(i[1]);
			}
			$('#loading').html('');
		}
	});
}

<?php 
$i = explode(',', $detail[program]);
if (in_array('JHT', $i)) { ?>
document.pilihProgram.JHT.checked = true;
JHTClick = 'Y';
<?php } ?>
document.pilihProgram.lokasiPekerjaan.value = '<?php echo $detail[lokasiPekerjaan] ?>';
document.pilihProgram.periodeSelect.value = '1';
document.pilihProgram.jkStart.value = '<?php echo $detail[jamAwal] ?>';
document.pilihProgram.jkStop.value = '<?php echo $detail[jamAkhir] ?>';

</script>

</body>
</html>