<?php

/*
 ****************************************************************************************************
 * API eToll Bakoelbuzz
 ****************************************************************************************************
 * Berikut adalah sampel penggunakan API Payment Point TopUp atau pengisian ulang eToll.
 *
 *
 ****************************************************************************************************
 */

require_once __DIR__ . '/../_PaymentPoint/Helper.php';

$helper = new Helper;

/*
 ****************************************************************************************************
 * Mitra Info
 ****************************************************************************************************
 */

// Request Sample
$mitraInfo = $helper->info();

// Response Sample
/*
Array
(
    [quota] => 99796454957
    [Description] => DEVELMODE BY MUSE
)
*/


/*
 ****************************************************************************************************
 * Product List
 ****************************************************************************************************
 */

// Request Sample
$product = $helper->options($cmd = 'ppTopupProductList', $data = 'ETOLL');

// Response Sample
/*
Array
(
    [category] => ETOLL
    [dataCount] => 13
    [productList] => Array
        (
            [0] => Array
                (
                    [productCode] => TOLM25
                    [productDesc] => SALDO ETOLL MANDIRI 25K
                    [productDenom] => 25000
                    [productPrice] => 26450
                )

            [1] => Array
                (
                    [productCode] => TOLM50
                    [productDesc] => SALDO ETOLL MANDIRI 50K
                    [productDenom] => 50000
                    [productPrice] => 51450
                )

            [2] => Array
                (
                    [productCode] => TOLM100
                    [productDesc] => SALDO ETOLL MANDIRI 100K
                    [productDenom] => 100000
                    [productPrice] => 102350
                )

            [3] => Array
                (
                    [productCode] => TOLM150
                    [productDesc] => SALDO ETOLL MANDIRI 150K
                    [productDenom] => 150000
                    [productPrice] => 151450
                )

            [4] => Array
                (
                    [productCode] => TOLM200
                    [productDesc] => SALDO ETOLL MANDIRI 200K
                    [productDenom] => 200000
                    [productPrice] => 201850
                )

            [5] => Array
                (
                    [productCode] => TOLM300
                    [productDesc] => SALDO ETOLL MANDIRI 300K
                    [productDenom] => 300000
                    [productPrice] => 301850
                )

            [6] => Array
                (
                    [productCode] => TOLM500
                    [productDesc] => SALDO ETOLL MANDIRI 500K
                    [productDenom] => 500000
                    [productPrice] => 501850
                )

            [7] => Array
                (
                    [productCode] => TOLBN10
                    [productDesc] => SALDO ETOLL BNI 10K
                    [productDenom] => 10000
                    [productPrice] => 11450
                )

            [8] => Array
                (
                    [productCode] => TOLBN20
                    [productDesc] => SALDO ETOLL BNI 20K
                    [productDenom] => 20000
                    [productPrice] => 21450
                )

            [9] => Array
                (
                    [productCode] => TOLBN50
                    [productDesc] => SALDO ETOLL BNI 50K
                    [productDenom] => 50000
                    [productPrice] => 51525
                )

            [10] => Array
                (
                    [productCode] => TOLBN100
                    [productDesc] => SALDO ETOLL BNI 100K
                    [productDenom] => 100000
                    [productPrice] => 101550
                )

            [11] => Array
                (
                    [productCode] => TOLBN250
                    [productDesc] => SALDO ETOLL BNI 250K
                    [productDenom] => 250000
                    [productPrice] => 251525
                )

            [12] => Array
                (
                    [productCode] => TOLBN500
                    [productDesc] => SALDO ETOLL BNI 500K
                    [productDenom] => 500000
                    [productPrice] => 501600
                )

        )

)

*/

// Topup Gojek
$topup = $helper->topup($productCode = 'TOLM25', $idpel = '101000114217');

// Response Topup
/*
Array
(
    [responseCode] => 00
    [message] => BERHASIL
    [idpel] => 101000114217
    [voucher] => 
    [ref] => 952236503
    [trxID] => 6172662
)

*/

print_r($mitraInfo);
echo "\n\n";

print_r($product);
echo "\n\n";

print_r($topup);
echo "\n\n";

