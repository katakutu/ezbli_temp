<?php
require_once __DIR__ . '/../_Pulsa/Helper.php';

$helper = new Helper;

if (!empty($_POST))
	$ii = $helper->payment($_POST);

print_r($ii);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Sampel API Pulsa</title>
</head>
<body>
	Server Respon : <br/><br/>
	<?php

	//Respon server ini silahkan dipakai mana yang dianggap perlu.
	echo "Status Code : ".$ii['status'].'<br/>';
	echo "Status Description : ".$ii['msg'].'<br/>';
	echo "No Handphone : ".$ii['msisdn'].'<br/>';
	echo "Harga : ".$ii['price'].'<br/>';
	echo "TRXID : ".$ii['trxID'].'<br/>';
	?>
</body>
</html>