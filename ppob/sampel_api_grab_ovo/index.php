<?php

/*
 ****************************************************************************************************
 * API Grab Ovo Bakoelbuzz
 ****************************************************************************************************
 * Berikut adalah sampel penggunakan API Payment Point TopUp atau pengisian ulang Grab OVO.
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
$product = $helper->options($cmd = 'ppTopupProductList', $data = 'GRAB');

// Response Sample
/*
Array
(
    [category] => GRAB
    [dataCount] => 8
    [productList] => Array
        (
            [0] => Array
                (
                    [productCode] => GRAB100
                    [productDesc] => SALDO GRAB-OVO 100K
                    [productDenom] => 
                    [productPrice] => 101925
                )

            [1] => Array
                (
                    [productCode] => GRAB150
                    [productDesc] => SALDO GRAB-OVO 150K
                    [productDenom] => 
                    [productPrice] => 151925
                )

            [2] => Array
                (
                    [productCode] => GRAB20
                    [productDesc] => SALDO GRAB-OVO 20K
                    [productDenom] => 
                    [productPrice] => 21900
                )

            [3] => Array
                (
                    [productCode] => GRAB200
                    [productDesc] => SALDO GRAB-OVO 200K
                    [productDenom] => 
                    [productPrice] => 201950
                )

            [4] => Array
                (
                    [productCode] => GRAB25
                    [productDesc] => SALDO GRAB-OVO 25K
                    [productDenom] => 
                    [productPrice] => 26900
                )

            [5] => Array
                (
                    [productCode] => GRAB300
                    [productDesc] => SALDO GRAB-OVO 300K
                    [productDenom] => 
                    [productPrice] => 301950
                )

            [6] => Array
                (
                    [productCode] => GRAB50
                    [productDesc] => SALDO GRAB-OVO 50K
                    [productDenom] => 
                    [productPrice] => 51900
                )

            [7] => Array
                (
                    [productCode] => GRAB500
                    [productDesc] => SALDO GRAB-OVO 500K
                    [productDenom] => 
                    [productPrice] => 501950
                )

        )

)

*/

// Topup Gojek
$topup = $helper->topup($productCode = 'GRAB20', $idpel = '08123456789');

// Response Topup
/*
Array
(
    [responseCode] => 00
    [message] => BERHASIL
    [idpel] => 08123456789
    [voucher] => Voucher Code =24768271, Voucher Password=49678-00905-86487-19636-76298
    [ref] => 505264128
    [trxID] => 7444741
)


*/

print_r($mitraInfo);
echo "\n\n";

print_r($product);
echo "\n\n";

print_r($topup);
echo "\n\n";

