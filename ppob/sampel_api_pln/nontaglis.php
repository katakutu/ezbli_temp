<?php

require_once 'class.php';

$class	= new bbuzzPaymentPoint();

/*
 * PLN NONTAGLIS 																						
 * ===========
 *
 * Tester ID Pelanggan:
 * - 1100000000001 
 * - 1100000000002
 * - 1100000000881 (Tagihan sudah terbayar)
 */

# Inquiry
$i = $class->inquiry('PLNNONTAGLISB', '1200000000012', '', '');

/*
 * Contoh respon inquiry:

	Array
	(
		[responseCode] => 00
		[message] => Successful
		[data] => Array
	        (
				[registrationNumber] => 1100000000001
				[transactionName] => PERUBAHAN DAYA
				[registrationDate] => 20160830
				[nama] => SUBSRIBER NAME
				[subscriberID] => 123456789012
				[nilaiTagihan] => 500000
				[admin] => 5000
				[total] => 505000
	        )

		[totalTagihan] => 505000
		[productCode] => PLNNONTAGLISB
		[refID] => 224884
	)

 * Data yang harus ada dalam tampilan inquiry PLN Non Tagihan Listrik:
 *
 * TRANSAKSI:	PERUBAHAN DAYA
 * NO REGISTRASI: 	1100000000001
 * TGL REGISTRASI:	30 AGU 16
 * NAMA:	SUBSRIBER NAME
 * IDPEL: 	123456789012
 * BIAYA PLN: 	Rp. 500.000
 * ADMIN: 	Rp. 5.000
 * TOTAL BAYAR: 	Rp. 505.000
 */

# Payment
$p = $class->payment('PLNNONTAGLISB', $i['refID'], intval($i['totalTagihan']));

/*
 * Contoh respon payment voucher PLN Postpaid:
 
	Array
	(
		[responseCode] => 00
		[message] => Successful
		[data] => Array
			(
				[registrationNumber] => 1100000000001
				[transactionName] => PERUBAHAN DAYA
				[registrationDate] => 20160830
				[nama] => SUBSRIBER NAME
				[subscriberID] => 123456789012
				[ref] => 004221C9246309AC2434DEC52E2641C0
				[nilaiTagihan] => 500000
				[admin] => 5000
				[total] => 505000
			)

		[totalTagihan] => 505000
		[infotext] => RINCIAN TAGIHAN DAPAT DIAKSES DI www.pln.co.id ATAU PLN TERDEKAT
	)

 * Format struk mengikuti contoh file 'struk nontaglis.pdf'
 */

print_r($i);
print "\n";
print_r($p);