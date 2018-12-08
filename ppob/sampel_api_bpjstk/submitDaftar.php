<?php
require_once ("api.connect.php");

$reg = registration($_POST);

if ($reg[status] == '00') {
	header("location: inquiryKodeIuran.php?kodeIuran=".$reg[kodeIuran]);
} else {
	echo $reg[msg];
}
?>
