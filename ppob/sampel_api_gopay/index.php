<?php

/*
 ***********************************************************************
 List of Product Code
 ***********************************************************************
 ┌──────────────────┬──────────────────────────────────────────────────┐
 │ Code 			│ Description									   │
 ├──────────────────┼──────────────────────────────────────────────────┤
 │ HGD20            │ TopUp Driver Gojek 20.000                        │
 │ HGD50            │ TopUp Driver Gojek 50.000                        │
 │ HGP20            │ TopUp GoPay 20K                                  │
 │ HGD100           │ TopUp Driver Gojek 100.000                       │
 │ HGD150           │ TopUp Driver Gojek 150.000                       │
 │ HGD200           │ TopUp Driver Gojek 200.000                       │
 │ PGD20            │ PROMO TopUp Driver Gojek 20.000                  │
 │ PGD50            │ PROMO TopUp Driver Gojek 50.000                  │
 │ PGD75            │ PROMO TopUp Driver Gojek 75.000                  │
 │ PGD100           │ PROMO TopUp Driver Gojek 100.000                 │
 │ PGD150           │ PROMO TopUp Driver Gojek 150.000                 │
 │ PGD200           │ PROMO TopUp Driver Gojek 200.000                 │
 │ HGP25            │ TopUp GoPay 25.000                               │
 │ HGP40            │ TopUp GoPay 40.000                               │
 │ HGP50            │ TopUp GoPay 50.000                               │
 │ HGP75            │ TopUp GoPay 75.000                               │
 │ HGP100           │ TopUp GoPay 100.000                              │
 │ HGP150           │ TopUp GoPay 150.000                              │
 │ HGP250           │ TopUp GoPay 250.000                              │
 │ HGP500           │ TopUp GoPay 500.000                              │
 │ PGP5             │ PROMO TopUp GoPay 5.000                          │
 │ PGP10            │ PROMO TopUp GoPay 10.000                         │
 │ PGP20            │ PROMO TopUp GoPay 20.000                         │
 │ PGP25            │ PROMO TopUp GoPay 25.000                         │
 │ PGP50            │ PROMO TopUp GoPay 50.000                         │
 │ PGP75            │ PROMO TopUp GoPay 75.000                         │
 │ PGP100           │ PROMO TopUp GoPay 100K                           │
 │ PGP150           │ PROMO TopUp GoPay 150.000                        │
 │ PGP200           │ PROMO TopUp GoPay 200.000                        │
 └──────────────────┴──────────────────────────────────────────────────┘
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


// List Produk Gojek
$product = $helper->options($cmd = 'ppTopupProductList', $data = 'GOJEK');

// Response Log
/*
Array
(
    [category] => GOJEK
    [dataCount] => 29
    [productList] => Array
        (
            [0] => Array
                (
                    [productCode] => HGD20
                    [productDesc] => TopUp Driver Gojek 20.000
                    [productDenom] => 20.000
                    [productPrice] => 21800
                )

            [1] => Array
                (
                    [productCode] => HGD50
                    [productDesc] => TopUp Driver Gojek 50.000
                    [productDenom] => 50.000
                    [productPrice] => 51800
                )

            [2] => Array
                (
                    [productCode] => HGP20
                    [productDesc] => TopUp GoPay 20K
                    [productDenom] => 20.000
                    [productPrice] => 21950
                )

            [3] => Array
                (
                    [productCode] => HGD100
                    [productDesc] => TopUp Driver Gojek 100.000
                    [productDenom] => 100.000
                    [productPrice] => 101800
                )

            [4] => Array
                (
                    [productCode] => HGD150
                    [productDesc] => TopUp Driver Gojek 150.000
                    [productDenom] => 150.000
                    [productPrice] => 151800
                )

            [5] => Array
                (
                    [productCode] => HGD200
                    [productDesc] => TopUp Driver Gojek 200.000
                    [productDenom] => 300.000
                    [productPrice] => 201800
                )

            [6] => Array
                (
                    [productCode] => PGD20
                    [productDesc] => PROMO TopUp Driver Gojek 20.000
                    [productDenom] => 20.000
                    [productPrice] => 21350
                )

            [7] => Array
                (
                    [productCode] => PGD50
                    [productDesc] => PROMO TopUp Driver Gojek 50.000
                    [productDenom] => 50.000
                    [productPrice] => 51400
                )

            [8] => Array
                (
                    [productCode] => PGD75
                    [productDesc] => PROMO TopUp Driver Gojek 75.000
                    [productDenom] => 75.000
                    [productPrice] => 76450
                )

            [9] => Array
                (
                    [productCode] => PGD100
                    [productDesc] => PROMO TopUp Driver Gojek 100.000
                    [productDenom] => 100.000
                    [productPrice] => 101450
                )

            [10] => Array
                (
                    [productCode] => PGD150
                    [productDesc] => PROMO TopUp Driver Gojek 150.000
                    [productDenom] => 150.000
                    [productPrice] => 151650
                )

            [11] => Array
                (
                    [productCode] => PGD200
                    [productDesc] => PROMO TopUp Driver Gojek 200.000
                    [productDenom] => 200.000
                    [productPrice] => 201650
                )

            [12] => Array
                (
                    [productCode] => HGP25
                    [productDesc] => TopUp GoPay 25.000
                    [productDenom] => 25.000
                    [productPrice] => 26950
                )

            [13] => Array
                (
                    [productCode] => HGP40
                    [productDesc] => TopUp GoPay 40.000
                    [productDenom] => 40.000
                    [productPrice] => 41950
                )

            [14] => Array
                (
                    [productCode] => HGP50
                    [productDesc] => TopUp GoPay 50.000
                    [productDenom] => 50.000
                    [productPrice] => 51950
                )

            [15] => Array
                (
                    [productCode] => HGP75
                    [productDesc] => TopUp GoPay 75.000
                    [productDenom] => 75.000
                    [productPrice] => 76950
                )

            [16] => Array
                (
                    [productCode] => HGP100
                    [productDesc] => TopUp GoPay 100.000
                    [productDenom] => 100.000
                    [productPrice] => 101950
                )

            [17] => Array
                (
                    [productCode] => HGP150
                    [productDesc] => TopUp GoPay 150.000
                    [productDenom] => 150.000
                    [productPrice] => 151950
                )

            [18] => Array
                (
                    [productCode] => HGP250
                    [productDesc] => TopUp GoPay 250.000
                    [productDenom] => 250.000
                    [productPrice] => 251950
                )

            [19] => Array
                (
                    [productCode] => HGP500
                    [productDesc] => TopUp GoPay 500.000
                    [productDenom] => 500.000
                    [productPrice] => 501950
                )

            [20] => Array
                (
                    [productCode] => PGP5
                    [productDesc] => PROMO TopUp GoPay 5.000
                    [productDenom] => 5.000
                    [productPrice] => 6350
                )

            [21] => Array
                (
                    [productCode] => PGP10
                    [productDesc] => PROMO TopUp GoPay 10.000
                    [productDenom] => 10.000
                    [productPrice] => 11350
                )

            [22] => Array
                (
                    [productCode] => PGP20
                    [productDesc] => PROMO TopUp GoPay 20.000
                    [productDenom] => 20.000
                    [productPrice] => 21350
                )

            [23] => Array
                (
                    [productCode] => PGP25
                    [productDesc] => PROMO TopUp GoPay 25.000
                    [productDenom] => 25.000
                    [productPrice] => 26400
                )

            [24] => Array
                (
                    [productCode] => PGP50
                    [productDesc] => PROMO TopUp GoPay 50.000
                    [productDenom] => 50.000
                    [productPrice] => 51400
                )

            [25] => Array
                (
                    [productCode] => PGP75
                    [productDesc] => PROMO TopUp GoPay 75.000
                    [productDenom] => 75.000
                    [productPrice] => 76450
                )

            [26] => Array
                (
                    [productCode] => PGP100
                    [productDesc] => PROMO TopUp GoPay 100K
                    [productDenom] => 100.000
                    [productPrice] => 101450
                )

            [27] => Array
                (
                    [productCode] => PGP150
                    [productDesc] => PROMO TopUp GoPay 150.000
                    [productDenom] => 150.000
                    [productPrice] => 151650
                )

            [28] => Array
                (
                    [productCode] => PGP200
                    [productDesc] => PROMO TopUp GoPay 200.000
                    [productDenom] => 200.000
                    [productPrice] => 201650
                )

        )

)*/

// Topup Gojek
$topup = $helper->topup($productCode = 'HGD20', $idpel = '101000114217');

// Response Topup
/*
Array
(
    [responseCode] => 00
    [message] => SUKSES - TEDDIE DIAN PATRIA 381521166274327
    [idpel] => 101000114217
    [ref] => 381521166274327
    [trxID] => 5690151
)

*/

print_r($mitraInfo);
echo "\n\n";

print_r($product);
echo "\n\n";

print_r($topup);
echo "\n\n";

