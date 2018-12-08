<?php

require_once 'class.php';

$class	= new bbuzzPaymentPoint();

/*
 * PLN POSTPAIS 																						
 * ===========
 *
 * Tester ID Pelanggan:
 * - 530000000001 (Tagihan 1 bulan)
 * - 530000000002 (Tagihan 1 bulan, payment sudah terbayar)
 * - 530000000004 (Tagihan 4 bulan)
 * - 530000000024 (8 lembar tagihan)
 * - 520000000088 (Tagihan sudah dibayar)
 */

# Inquiry
$i = $class->inquiry('PLNPOSTPAIDB', '530000000001', '', '');

/*
 * Contoh respon inquiry:

	Array
	(
		[responseCode] => 00
		[message] => Successful
		[subscriberID] => 530000000001
		[nama] => SUBCRIBER NAME
		[tarif] => R1
		[daya] => 1300
		[lembarTagihanTotal] => 1
		[lembarTagihan] => 1
		[detilTagihan] => Array
			(
				[0] => Array
					(
						[periode] => 201608
						[nilaiTagihan] => 300000
						[denda] => 0
						[admin] => 2500
						[total] => 302500
					)

			)

		[totalTagihan] => 302500
		[productCode] => PLNPOSTPAIDB
		[refID] => 224854
	)


 
 * Data yang harus ada dalam tampilan inquiry PLN Postpaid:
 *
 * Nama: XXXXXXXXXXXXXXXXX
 * Tarif/Daya: XX/XXXXXVA
 * Lembar Tagihan: XX lembar
 *
 * ==================================================================
 * | Periode 	| Tagihan	| Denda 	| Admin 	| Sub Total 	|
 * ==================================================================
 * | 201701		| 300.000	| 0 	 	| 2.500		| 302.500		|
 * | dst...		| dst...	| dst... 	| dst...	| dst...		|
 * ==================================================================
 */

# Payment
$p = $class->payment('PLNPOSTPAIDB', $i['refID'], intval($i['totalTagihan']));

/*
 * Contoh respon payment voucher PLN Postpaid:
 
	Array
	(
		[responseCode] => 00
		[message] => Successful
		[subscriberID] => 530000000001
		[nama] => SUBCRIBER NAME
		[tarif] => R1
		[daya] => 1300
		[lembarTagihanSisa] => 0
		[lembarTagihan] => 1
		[detilTagihan] => Array
			(
				[0] => Array
					(
						[meterAwal] => 00080000
						[meterAkhir] => 00080000
						[periode] => 201608
						[nilaiTagihan] => 300000
						[denda] => 0
						[admin] => 2500
						[total] => 302500
						[fee] => 2300
					)

			)

		[totalTagihan] => 302500
		[refnumber] => 004212C9245F1BA43A77CEBD5CD5DA39
		[infoText] => RINCIAN TAGIHAN DAPAT DIAKSES DI www.pln.co.id ATAU PLN TERDEKAT
	)

 * Format struk mengikuti contoh file 'struk postpaid.pdf'
 * Note: 1 struk hanya untuk 1 bulan tagihan tidak bisa digabungkan
 *
 * Apabila didalam respon $i['lembarTagihanSisa'] valuenya lebih dari 0,
 * seperti contoh respon dibawah, maka dalam struk harus disertakan bahwa 
 * masih mempunyai sisa tagihan (maks 1x transaksi adalah 4 lembar tagihan),
 * contoh struk mengikuti file 'struk postpaid2.pdf'

	Array
	(
		[responseCode] => 00
		[message] => Successful
		[subscriberID] => 530000000024
		[nama] => SUBCRIBER NAME
		[tarif] => R1
		[daya] => 1300
		[lembarTagihanTotal] => 8
		[lembarTagihan] => 4
		[detilTagihan] => Array
	        (
	            [0] => Array
	                (
						[periode] => 201605
						[nilaiTagihan] => 300000
						[denda] => 0
						[admin] => 2500
						[total] => 302500
	                )

	            [1] => Array
	                (
						[periode] => 201606
						[nilaiTagihan] => 600000
						[denda] => 0
						[admin] => 2500
						[total] => 602500
	                )

	            [2] => Array
	                (
						[periode] => 201607
						[nilaiTagihan] => 900000
						[denda] => 0
						[admin] => 2500
						[total] => 902500
	                )

	            [3] => Array
	                (
						[periode] => 201608
						[nilaiTagihan] => 1200000
						[denda] => 0
						[admin] => 2500
						[total] => 1202500
	                )

	        )

		[totalTagihan] => 3010000
		[productCode] => PLNPOSTPAIDB
		[refID] => 224875
	)

 */

print_r($i);
print "\n";
print_r($p);