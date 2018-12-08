<?php

/*
 ***********************************************************************
 List of Product Code
 ***********************************************************************
 ┌──────────────────┬──────────────────────────────────────────────────┐
 │ Code 			│ Description									   │
 ├──────────────────┼──────────────────────────────────────────────────┤
 │ BPJSKES          │  BPJS Kesehatan                                  │
 └──────────────────┴──────────────────────────────────────────────────┘

 */

 /*
 ***********************************************************************
 Sampel No VA
 ***********************************************************************
 */

/*
 ╔══════════════════════════════════════════════════════════════════════╗
 ║ Sample PHP-SOAP XML with NuSoap libraries							║
 ╚══════════════════════════════════════════════════════════════════════╝
 */

require_once __DIR__ . '/ApiHelper.php';

$helper = new ApiHelper;

// Request Mitra Info
$mitraInfo = $helper->mitraInfo();

// Response Mitra Info
/*
Array
(
    [quota] => 99796454957
    [Description] => DEVELMODE BY MUSE
)
*/


// Inquiry
$inquiry = $helper->inquiry($productCode = 'BPJSKES', $idpel = '0001436861946', $jumlahBulan = '01', $noHP = '08123456789');

// Response Inquiry
/*
Array
(
    [responseCode] => 00
    [message] => Success
    [noVA] => 0000001436861946
    [nama] => KETUT AGUNG LUHUR SUCIPTO
    [namaCabang] => DENPASAR
    [jumlahPeriode] => 01
    [jumlahPeserta] => 3
    [detailPeserta] => Array
        (
            [0] => Array
                (
                    [noPeserta] => 0000001436861946
                    [nama] => KETUT AGUNG LUHUR SUCIPTO
                    [premi] => 51000
                    [saldo] => 0
                )

            [1] => Array
                (
                    [noPeserta] => 0000001436869282
                    [nama] => WAYAN MAHYUNI
                    [premi] => 51000
                    [saldo] => 0
                )

            [2] => Array
                (
                    [noPeserta] => 0000001436870766
                    [nama] => PUTU DIMAS PRAMUDITA
                    [premi] => 51000
                    [saldo] => 0
                )

        )

    [tagihan] => 153000
    [admin] => 2500
    [total] => 155500
    [customerData] => 2147483647
    [productCode] => BPJSKES
    [refID] => 7148584
)
*/

// Payment PDAM
$payment = $helper->payment($productCode = 'BPJSKES', $refID = $inquiry['refID'], $inquiry['total']);

// Response Payment
/*
Array
(
    [responseCode] => 00
    [message] => Success
    [noVA] => 0000001436861946
    [nama] => KETUT AGUNG LUHUR SUCIPTO
    [noReferensi] => 4FE0C3745FA240D795E093D7F3D26774
    [jumlahPeriode] => 01
    [jumlahPeserta] => 3
    [tagihan] => 153000
    [admin] => 2500
    [total] => 155500
    [info] =>
    [customerData] => 2147483647
)

*/

// Log Payment
$log = $helper->options($cmd = 'getPaymentData', $data = $inquiry['refID']);

// Response Log Payment
/*
Array
(
    [responseCode] => 00
    [message] => Success
    [noVA] => 0000001436861946
    [nama] => KETUT AGUNG LUHUR SUCIPTO
    [noReferensi] => 4FE0C3745FA240D795E093D7F3D26774
    [jumlahPeriode] => 01
    [jumlahPeserta] => 3
    [tagihan] => 153000
    [admin] => 2500
    [total] => 155500
    [info] =>
    [customerData] => 2147483647
)
*/

print_r($mitraInfo);
echo "\n\n";

print_r($inquiry);
echo "\n\n";

print_r($payment);
echo "\n\n";

print_r($log);
echo "\n\n";
