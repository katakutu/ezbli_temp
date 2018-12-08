<?php
require_once ("api.connect.php");
$sP = pilihProgram($_POST);

header("location: inquiryKodeIuran.php?kodeIuran=".$sP[kodeIuran]);

?>