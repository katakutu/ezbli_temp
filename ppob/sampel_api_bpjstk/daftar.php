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
<form action="lanjutDaftar.php" method="post">
<table>
<tbody>
  <tr>
    <td width="100px" >No KTP - eL</td>
    <td><input type="text" name="nik" /></td>
  </tr>
  <tr>
    <td>Nama Lengkap</td>
    <td><input type="text" name="namaKtp" style="width:300px" /></td>
  </tr>
  <tr>
    <td>Tanggal Lahir</td>
    <td><input type="text" name="tgLahir" id="datepicker" /></td>
  </tr>
  <tr>
    <td>Nomor Ponsel</td>
    <td><input type="text" name="noPonsel" style="width:250px" /></td>
  </tr>
  <tr>
    <td></td>
    <td><button type="submit">Lanjutkan</button>
    </td>
  </tr>
</tbody>
</table>
</form>

<script>
$(document).ready(function() {
	
	$( "#datepicker" ).datepicker({
		showOn: "button",
		buttonImage: "images/calendar.gif",
		buttonImageOnly: true, dateFormat: 'dd-mm-yy',
		yearRange: "0:+2",
		changeMonth: true,
		changeYear: true
	});
	
});
</script>

</body>
</html>