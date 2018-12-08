<?php
require_once ("api.connect.php");

$by = bayarIuran($_POST[kodeIuran]);

if ($by[status] == '00') { // sukses
	header("location: cetakUlang.php?kodeIuran=".$by[kodeIuran]);
} else {
	echo $by[msg];
}
?>