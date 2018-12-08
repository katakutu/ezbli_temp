<?php
error_reporting(E_ALL & ~E_NOTICE);
require 'api.connect.php';


//$i = mitraInfo();
//$i = productListGame('');
$i = paymentVoucherGame('G578568', '9500', uniqid());
//$i = voucherGameStatus('0085652636528', '8835001520120712816899');

print_r($i);


?>