<?php

require_once 'class.php';

$class	= new bbuzzPaymentPoint();

/*
 * PLN PREPAID 																						
 * ===========
 *
 * Tester ID Pelanggan:
 * - 04000000001 (11 digit)
 * - 530000000001 (12 digit / No Meter)
 * - 05000000168 (Un Sold voucher dan manual advice sukses)
 * - 05000002168 (Manual advice refund)
 */

# Inquiry
$i = $class->inquiry('PLNPREPAIDB', '04000000001', '', '');

/*
 * Contoh respon inquiry:

	Array
	(
	    [responseCode] => 00
	    [message] => Successful
	    [data] => Array
	        (
	            [msn] => 05000000168
	            [subscriberID] => 523013161974
	            [nama] => SUBCRIBER NAME
	            [tarif] => R1
	            [daya] => 1300
	            [admin] => 2500
	        )

	    [powerPurchaseDenom] => Array
	        (
	            [0] => 20000
	            [1] => 50000
	            [2] => 100000
	            [3] => 200000
	            [4] => 500000
	            [5] => 1000000
	            [6] => 5000000
	            [7] => 10000000
	            [8] => 50000000
	        )

	    [powerPurchaseUnsold] => Array
	        (
	            [0] => 20000
	        )

	    [productCode] => PLNPREPAIDB
	    [refID] => 224812
	)
 
 * Data yang harus ada dalam tampilan inquiry PLN Prepaid:
 *
 * MSN: XXXXXXXXXXX
 * IDPEL: XXXXXXXXXXXXX
 * Nama: XXXXXXXXXXXXXXXXX
 * Tarif/Daya: XX/XXXXXVA
 *
 * ==========================================================
 * | Opsi 	| Nominal			| Admin 	| Sub Total 	|
 * ==========================================================
 * | x 		| 20.000 			| 2.500 	| 22.500 		|
 * | dst...	| dst...			| dst... 	| dst...		|
 * ==========================================================
 *
 * jika terdapat field $i['powerPurchaseUnsold'] dalam respon inquiry juga harus
 * ditampilkan dengan status "Un Sold",
 *
 * ==========================================================
 * | x 		| 20.000 (Un Sold)	| 2.500 	| 22.500 		|
 * ==========================================================
 */

# Payment
$p = $class->payment('PLNPREPAIDB', $i['refID'], $i['powerPurchaseDenom'][0] + $i['data']['admin']);

/*
 * Contoh respon payment voucher PLN Prepaid:
 
	Array
	(
		[responseCode] => 00
		[message] => Successful
		[data] => Array
			(
				[msn] => 11000000086
				[subscriberID] => 530000000001
				[nama] => SUBCRIBER NAME           
				[tarif] => R1
				[daya] => 1300
				[ref] => 004221C9259F803E176984C5F9CADCB1
				[admin] => 2500
				[nominal] => 20000
				[total] => 22500
				[tokenNumber] => 12312312311231231231
				[biayaMeterai] => 0,00
				[ppn] => 0,00
				[ppj] => 0,00
				[angsuran] => 0,00
				[rpToken] => 12.000,00
				[kwh] => 100,0
			)

		[totalTagihan] => 22500
		[infotext] => RINCIAN TAGIHAN DAPAT DIAKSES DI www.pln.co.id ATAU PLN TERDEKAT
	)

* Format struk mengikuti contoh file 'struk prepaid.pdf'
*/

# Payment  Un Sold voucher
// $p = $class->payment('PLNPREPAIDB', $i['refID'], $i['powerPurchaseUnsold'] + $i['data']['admin'], '1');

/*
 * Apabila mendapatkan respon gangguan seperti contoh dibawah silahkan lakukan advice manual
 * dengan command ('cmd') yang harus digunakan adalah 'manualAdvicePrepaid', dan 
 * data ('data') bertipe string, json encode dari array productCode, 
 * hashID yang didapatkan saat melakukan payment

	Array
	(
		[responseCode] => 9983
		[message] => Pembayaran terjadi Gangguan, Lakukan Advice Manual, transaksi tetap dilakukan pendebetan
		[manualAdviceHashID] => ada5f7879c98cb2b63dce9f57c2087e57b46bdeb
	)
 
 */

# Manual Advice
// $o = $class->options('manualAdvicePrepaid', json_encode(array('productCode' => 'PLNPREPAIDB', 'hashID' => $p['manualAdviceHashID'])));

/*
 * Jika manual advice sukses, maka akan mendapatkan respon sama dengan payment
 * sukses dan jika gagal akan mendapatkan respon refund.
 */

print_r($i);
print "\n";
print_r($p);
print "\n";
// print_r($o);