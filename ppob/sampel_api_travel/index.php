<?php

require_once __DIR__ . '\ApiTravel.php';

$api = new ApiTravel;


//$i = $api->travelGetAgen();
//$i = $api->travelGetKeberangkatan('DTR');

//$i = $api->travelGetKedatangan('DTR', '67981c44e8f6754c2dedbe527cb6d1f1159fa13f');
//$i = $api->travelGetJadwalKeberangkatan('DTR', '67981c44e8f6754c2dedbe527cb6d1f1159fa13f', '2ef9dba3188a3caa9266fa2797e941078688adfb', '2016-02-15', '8');

//$i = $api->travelGetKeberangkatan('XTR');

//$i = $api->travelGetKedatangan('XTR', '3afb485294ec42b95c1deb7b38b9063f7e297083');
//$i = $api->travelGetJadwalKeberangkatan('XTR', '3afb485294ec42b95c1deb7b38b9063f7e297083', 'da93e4373ddb58179300f52b099ebed64ce71916', '2016-03-10', '1');

//$j = $api->travelGetMapKursi('DTR', 'BIP-LBS20.00', '2016-02-28', '8', '77');

//$i = $api->travelBook('DTR', 'BIP-LBS20.00', '2016-01-22', 'Muse', 'Jogja', '0274485636', 'muse@bakoelkomputer.info', '1', '1', 'muse');

//$i = $api->travelCekReservasi('2345593TTX');
//$i = $api->travelCekReservasi('00F6EB0TTX');
//$i = $api->travelCekReservasi('63D98BCTTX');


//$i = $api->travelPayBook('15D5FD6TTX', '75000');

//print_r($i);
//die;

//print_r($j);

//echo json_encode($j);

/*
header('Content-Type: application/pdf');
header("Content-Transfer-Encoding: Binary");
//header("Content-disposition: attachment; filename = tiket.pdf");// nama file sebagai tiket.pdf

echo base64_decode($i[tiketPdf]);
*/

?>