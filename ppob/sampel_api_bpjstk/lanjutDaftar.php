<?php
require_once ("api.connect.php");

$verify = array(
	'nik' => $_POST['nik'],
	'namaKtp' => $_POST['namaKtp'],
	'tgLahir' => $_POST['tgLahir'],
	'noPonsel' => $_POST['noPonsel']
);

$out = verifyKTPeL($verify);

if ($out[status] != '00') {
	echo $out[msg];
	exit();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui.css" media="screen" />

<script src="//code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-3.0.0.min.js"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

</head>

<body>
<form method="post" action="submitDaftar.php" name="lanjutDaftar">
    <table width="100%" border="0">
    <tbody>
      <tr>
        <td width="150px">No KTP - eL</td>
        <td><input type="text" name="nik" value="<?php echo $out['nik'] ?>" readonly="readonly" /></td>
      </tr>
      <tr>
        <td>Nama Lengkap</td>
        <td><input type="text" name="namaKtp" value="<?php echo $out['namaKtp'] ?>" readonly="readonly" /></td>
      </tr>
      <tr>
        <td>Tanggal Berakhir KTP</td>
        <td><input type="text" name="expNik" id="datepicker" /></td>
      </tr>
      <tr>
        <td>Tempat, Tanggal Lahir</td>
        <td>
        <input type="text" name="tempatLahir" value="<?php echo $out['tempatLahir'] ?>" readonly="readonly" />&nbsp;
        <input type="text" name="tgLahir" value="<?php echo $out['tgLahir'] ?>" readonly="readonly" style="width:110px" />
        </td>
      </tr>
      <tr>
        <td>Kota Domisili</td>
        <td><input type="text" name="kotaDomisili" value="<?php echo $out['kotaDomisili'] ?>" autocomplete="off" /></td>
      </tr>
      <tr>
        <td valign="top">Alamat</td>
        <td><textarea  name="alamat" id="alamat" cols="40" rows="3"><?php echo $out['alamat'] ?></textarea></td>
      </tr>
      <tr>
        <td>Kecamatan</td>
        <td><input type="text" name="kecamatan" value="<?php echo $out['kecamatan'] ?>" autocomplete="off" /></td>
      </tr>
      <tr>
        <td>Kelurahan</td>
        <td><input type="text" name="kelurahan" value="<?php echo $out['kelurahan'] ?>" autocomplete="off" /></td>
      </tr>
      <tr>
        <td>Kode Pos</td>
        <td><input   type="text" name="kodepos" value="<?php echo $out['kodepos'] ?>" autocomplete="off" /></td>
      </tr>
      <tr>
        <td>No Ponsel</td>
        <td><input type="text" name="noPonsel" id="noPonselID" value="<?php echo $out['noPonsel'] ?>" readonly="readonly" /></td>
      </tr>
      <tr>
        <td>Email</td>
        <td><input type="text" name="email" autocomplete="off" /></td>
      </tr>
      <tr>
        <td>Pilih Program</td>
        <td>
        <label for="f" style="margin-right:20px"><input id="f" type="checkbox" name="JHT" value="Y" onchange="jhtClick(this)" />Jaminan Hari Tua <strong style="color:#8FD32A">(JHT)</strong></label><br />
        <label for="c" style="margin-right:20px"><input id="c" type="checkbox" name="JKK" value="Y" checked="checked" onchange="this.checked = true;" />Jaminan Kecelakaan Kerja <strong style="color:#8FD32A">(JKK)</strong></label><br />
        <label for="d" style="margin-right:20px"><input id="d" type="checkbox" name="JKM" value="Y" checked="checked" onchange="this.checked = true;" />Jaminan Kematian <strong style="color:#8FD32A">(JKM)</strong></label>
        </td>
      </tr>
      <tr>
        <td>Periode Program</td>
        <td>
        <select name="periodeSelect" >
            <option value="">Pilih Program</option>
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
        <td><input type="text" name="jmPenghasilan" autocomplete="off" /></td>
      </tr>
      <tr>
        <td>Lokasi Pekerjaan</td>
        <td>
        <select name="lokasiPekerjaan">
            <option value="">Pilih Lokasi Pekerjaan</option>
            <?php
            $lok = getLokasiKerja();
            for($idx = 0; $idx<sizeof($lok); $idx++) {
            ?>
            <option value="<?php echo $lok[$idx][kode] ?>"><?php echo $lok[$idx][nama] ?></option>
            <?php } ?>
        </select>            
        </td>
      </tr>
      <tr>
        <td></td>
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
        <td valign="top">Jenis Pekerjaan </td>
        <td><input type="text" name="jenisPekerjaan" autocomplete="off" style="width:390px" /><br />(Maks 3 pekerjaan contoh: Tukang, Pedagang, Petani)
        </td>
      </tr>
      <tr>
        <td>Awal Jam Kerja</td>
        <td><input type="text" name="jkStart" id="jkStartSpinner" value="00:00" /></td>
      </tr>
      <tr>
        <td>Jam Akhir Kerja</td>
        <td><input type="text" name="jkStop" id="jkStopSpinner"  value="00:00" /></td>
      </tr>
      <tr>
        <td>Kode Notifikasi SMS</td>
        <td><input type="text" name="notifySMS" autocomplete="off" /></td>
      </tr>
      <tr>
        <td colspan="2" valign="center" align="center" style="background-color:#04A854; color:#FFF">- Pilihan Lokasi Kantor BPJSTK -</td>
      </tr>
      <tr>
        <td>Provinsi</td>
        <td>
        <select name="kodeProvKantor" onchange="provinsiChange(this)" style="width:250px">
            <option value="" selected="selected">Pilih Provinsi</option>
        <?php 
        $prop = getProvinsiList();			
        for ($i=0; $i<sizeof($prop); $i++) { ?>
            <option value="<?php echo $prop[$i][kode] ?>"><?php echo $prop[$i][provinsi] ?></option>
        <?php } ?>
        </select>
        </td>
      </tr>
      <tr>
        <td>Kabupaten / Kota</td>
        <td><select id="selectKab" name="kodeKabKantor" onchange="kabupatenChange(this)" style="width:250px"></select></td>
      </tr>
      <tr>
        <td>Kantor Cabang</td>
        <td><select name="kodeKantorCab" id="selectKacab" style="width:250px"></select></td>
      </tr>
      <tr>
        <td></td>
        <td><button type="submit">Daftar</button></td>
      </tr>
    </tbody>
    </table>
</form>

<script>
$( "#datepicker" ).datepicker({
	showOn: "button",
	buttonImage: "images/calendar.gif",
	buttonImageOnly: true, dateFormat: 'dd-mm-yy',
	yearRange: "0:+2",
	changeMonth: true,
	changeYear: true
});
	
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
		data: 'q=hitungIuran&jmPenghasilan=' + document.lanjutDaftar.jmPenghasilan.value + '&JHT=' + JHTClick + '&lokasiPerkerjaan=' + document.lanjutDaftar.lokasiPekerjaan.value + '&periodeSelect=' + document.lanjutDaftar.periodeSelect.value ,
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

function provinsiChange(obj) {
	$("#selectKab").empty();
	$("#selectKacab").empty();
	if (obj.value == '') return false;
	$.ajax({
		type: 'POST',
		url: 'ajax.php',
		data: 'q=getKacabKabKota&dat=' + obj.value,
		success: function(data) {
			i = data.split(';');
			$("#selectKab").append($('<option>', {value:'', text: 'Pilih Kabupaten'}));
			for (var ii=0; ii<i.length; ii++) { 
				jj = i[ii].split('|');
				$("#selectKab").append($('<option>', {value:jj[0], text: jj[1]}));
			}
		}
	});
}

function kabupatenChange(obj) {
	$("#selectKacab").empty();
	if (obj.value == '') return false;
	$.ajax({
		type: 'POST',
		url: 'ajax.php',
		data: 'q=getKacab&dat=' + obj.value,
		success: function(data) { 
			i = data.split(';');
			$("#selectKacab").append($('<option>', {value:'', text: 'Pilih Kantor Cabang'}));
			for (var ii=0; ii<i.length; ii++) { 
				jj = i[ii].split('|');
				$("#selectKacab").append($('<option>', {value:jj[0], text: jj[1]}));
			}
		}
	});
}

</script>

</body>
</html>