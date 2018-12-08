<?php

require_once __DIR__ . '/../_Tiket/Helper.php';

$helper = new Helper;

// Mitra Info
$info = $helper->info();
echo 'Mitra info :'. PHP_EOL;
print_r($info);
echo PHP_EOL;

// List stasiun
$station = $helper->kai_station();
echo 'List station :'. PHP_EOL;
print_r($station);
echo PHP_EOL;

// Search jadwal
echo 'Jadwal kereta :'. PHP_EOL;
$data['asal'] = 'GMR';
$data['tujuan'] = 'BD';
$data['tanggal'] = date('Y-m-d', strtotime('+2 day'));
$search = $helper->kai_search($data);
print_r($search);
echo PHP_EOL;

// Seatmap
echo 'Map kursi :'. PHP_EOL;
$data['no_kereta'] = $search['schedule'][0]['trainNumber'];
$seatmap = $helper->kai_seatmap($data);
print_r($seatmap);
echo PHP_EOL;

// Seatmap subclass
echo 'Map subclass kursi :'. PHP_EOL;
$data['subclass'] = $seatmap['seat_map'][0][2][3][4];
$seatmap_subclass = $helper->kai_seatmap_subclass($data);
print_r($seatmap_subclass);
echo PHP_EOL;

// Booking tiket
echo 'Booking tiket :'. PHP_EOL;
$data['dewasa'] = 1;
$data['bayi'] = 1;
$data['penumpang'] = [
	'adult' => [
		[
			'adult_name' => 'MUSE',
			'adult_id' => '331234897887283674',
			'adult_date_of_birth' => '1945-08-17',
			'adult_phone' => '081234567890'
		]
	],
	'child' => NULL,
	'infant' => [
		[
			'infant_name' => 'LILY',
			'infant_date_of_birth' => '2018-08-17'
		]
	]
];
$data['pilih_kursi'] = 'manual';
$data['kode_gerbong'] = $seatmap_subclass['seatMap'][0][0];
$data['no_gerbong'] = $seatmap_subclass['seatMap'][0][1];
$data['kursi'] = $seatmap_subclass['seatMap'][0][2][2][2].$seatmap_subclass['seatMap'][0][2][2][3];
$book = $helper->kai_book($data);
print_r($book);
echo PHP_EOL;

// Issue tiket
echo 'Issue tiket :'. PHP_EOL;
$data['kode_booking'] = $book['bookingCode'];
$data['harga'] = $book['totalPrice'];
$issue = $helper->kai_issue($data);
print_r($issue);
echo PHP_EOL;

// Cek status tiket
echo 'Cek status tiket :'. PHP_EOL;
$data['kode_booking'] = $book['bookingCode'];
$cek = $helper->kai_check_book($data);
print_r($cek);
echo PHP_EOL;