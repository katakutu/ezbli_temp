<?php

/*
 ***********************************************************************
 List of Product Code
 ***********************************************************************
 ┌──────────────────┬──────────────────────────────────────────────────┐
 │ Code 			│ Description									   │
 ├──────────────────┼──────────────────────────────────────────────────┤
 │ PGN			    │ Perusahaan Gas Negara			                   │
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
$mitraIno = $helper->mitraInfo();

// Response Mitra Info
/*
Array
(
    [quota] => 99796454957
    [Description] => DEVELMODE BY MUSE
)
*/


/*
-----------------------------------------------------------------------
Sample for Inquiry
-----------------------------------------------------------------------
*/

// Inquiry Request
$i = $helper->inquiry($productCode = 'PGN', $idpel = '0110014679');

// Response Inquiry
/*
Array
(
    [responseCode] => 00
    [message] => sukses
    [idPel] => 0110014679
    [nama] => NGATIMUN
    [alamat] => -
    [periode] => 0917-1017
    [standAwal] => 006538
    [standAkhir] => 006573
    [pemakaian] => 0000000035
    [ref] => 55599-70237
    [tagihan] => 322002
    [admin] => 2500
    [totalTagihan] => 324502
    [productCode] => PGN
    [refID] => 3668686
)
*/

// Payment Request
$p = $helper->payment($productCode = 'PGN', $refID = $i['refID'], $nominal = $i['totalTagihan']);

// Response Payment
/*
Array
(
    [responseCode] => 00
    [message] => sukses
    [idPel] => 0110014679
    [nama] => NGATIMUN
    [alamat] => -
    [periode] => 0917-1017
    [standAwal] => 006538
    [standAkhir] => 006573
    [pemakaian] => 0000000035
    [tagihan] => 322002
    [admin] => 2500
    [totalTagihan] => 324502
    [ref] => 244183135447
    [tglBayar] => 10-11-2017 17:28:37
)
*/

print_r($i);
echo "\n\n";

print_r($p);
echo "\n\n";


// Log Payment Request
$log = $helper->options($cmd = 'getPaymentData', $data = $i['refID']);

// Response Log
/*
Array
(
    [responseCode] => 00
    [message] => sukses
    [idPel] => 0110014679
    [nama] => NGATIMUN
    [alamat] => -
    [periode] => 0917-1017
    [standAwal] => 006538
    [standAkhir] => 006573
    [pemakaian] => 0000000035
    [tagihan] => 322002
    [admin] => 2500
    [totalTagihan] => 324502
    [ref] => 244183135447
    [tglBayar] => 10-11-2017 17:28:37
)
*/

print_r($log);



/*
 ╔══════════════════════════════════════════════════════════════════════╗
 ║ Sample PHP-SOAP XML HTTP Post Request								║
 ╚══════════════════════════════════════════════════════════════════════╝
 */

$endpoint = 'http://117.102.64.238:1212/pp/index.php';


/*
-----------------------------------------------------------------------
Inquiry
-----------------------------------------------------------------------
*/

// Inquiry Request

// Set Inquiry Header
$iHeader = array(
	'Content-Type: text/xml; charset=ISO-8859-1',
	'SOAPAction: urn:ppInquiry#ppInquiry',
	'Authorization: Basic YmQ4NDQ2ZWFhNTc0YWIwMGFiNzIyNDlmOGIwNmE3NTk6YTMzNTU5MjA0NDRmYWU4ZWI4NGIxZjYzMDRiYmUxZDk='
);


/*
 Request Inquiry
 ┌──────────────┬────────┬──────────────┬───────────────────────────┐
 │ Field 		│ Type	 │ Format		│ Description 				│
 ├──────────────┼────────┼──────────────┼───────────────────────────┤
 │ productCode	│ String │ Alphanumeric │ Product Code			  	│
 │ idPel		│ String │ Numeric		│ Customer ID			    │
 │ idPel2		│ String │ Null 		│ 							│
 │ miscData		│ String │ Null 		│							│
 └──────────────┴────────┴──────────────┴───────────────────────────┘
 */


$iFields = '<?xml version="1.0" encoding="ISO-8859-1"?><SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tns="urn:Bakoelbuzz"><SOAP-ENV:Body><tns:ppInquiry xmlns:tns="urn:Bakoelbuzz"><productCode xsi:type="xsd:string">PGN</productCode><idPel xsi:type="xsd:string">0110014679</idPel><idPel2 xsi:type="xsd:string"></idPel2><miscData xsi:type="xsd:string"></miscData></tns:ppInquiry></SOAP-ENV:Body></SOAP-ENV:Envelope>';

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $iHeader);
curl_setopt($ch, CURLOPT_POSTFIELDS, $iFields);

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);

print_r($result);

// Response Inquiry
/*
<?xml version="1.0" encoding="UTF-8"?><SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/"><SOAP-ENV:Body><ns1:ppInquiryResponse xmlns:ns1="urn:Bakoelbuzz"><return><responseCode xsi:type="xsd:string">00</responseCode><message xsi:type="xsd:string">sukses</message><idPel xsi:type="xsd:string">0110014679</idPel><nama xsi:type="xsd:string">NGATIMUN</nama><alamat xsi:type="xsd:string">-</alamat><periode xsi:type="xsd:string">0917-1017</periode><standAwal xsi:type="xsd:string">006538</standAwal><standAkhir xsi:type="xsd:string">006573</standAkhir><pemakaian xsi:type="xsd:string">0000000035</pemakaian><ref xsi:type="xsd:string">55599-70237</ref><tagihan xsi:type="xsd:string">322002</tagihan><admin xsi:type="xsd:string">2500</admin><totalTagihan xsi:type="xsd:int">324502</totalTagihan><productCode xsi:type="xsd:string">PGN</productCode><refID xsi:type="xsd:string">3669213</refID></return></ns1:ppInquiryResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>1
*/

/*
-----------------------------------------------------------------------
Payment
-----------------------------------------------------------------------
*/

// Set Payment Header
$pHeader = array(
	'Content-Type: text/xml; charset=ISO-8859-1',
	'SOAPAction: urn:ppPayment#ppPayment',
	'Authorization: Basic YmQ4NDQ2ZWFhNTc0YWIwMGFiNzIyNDlmOGIwNmE3NTk6YTMzNTU5MjA0NDRmYWU4ZWI4NGIxZjYzMDRiYmUxZDk='
);

$pFields = '<?xml version="1.0" encoding="ISO-8859-1"?><SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tns="urn:Bakoelbuzz"><SOAP-ENV:Body><tns:ppPayment xmlns:tns="urn:Bakoelbuzz"><productCode xsi:type="xsd:string">PGN</productCode><refID xsi:type="xsd:string">3669213</refID><nominal xsi:type="xsd:string">324502</nominal><miscData xsi:type="xsd:string"></miscData></tns:ppPayment></SOAP-ENV:Body></SOAP-ENV:Envelope>';

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $pHeader);
curl_setopt($ch, CURLOPT_POSTFIELDS, $pFields);

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);

echo "\n\n $result \n\n";

// Response Inquiry
/*
<?xml version="1.0" encoding="UTF-8"?><SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/"><SOAP-ENV:Body><ns1:ppPaymentResponse xmlns:ns1="urn:Bakoelbuzz"><return><responseCode xsi:type="xsd:string">00</responseCode><message xsi:type="xsd:string">sukses</message><idPel xsi:type="xsd:string">0110014679</idPel><nama xsi:type="xsd:string">NGATIMUN</nama><alamat xsi:type="xsd:string">-</alamat><periode xsi:type="xsd:string">0917-1017</periode><standAwal xsi:type="xsd:string">006538</standAwal><standAkhir xsi:type="xsd:string">006573</standAkhir><pemakaian xsi:type="xsd:string">0000000035</pemakaian><tagihan xsi:type="xsd:string">322002</tagihan><admin xsi:type="xsd:string">2500</admin><totalTagihan xsi:type="xsd:int">324502</totalTagihan><ref xsi:type="xsd:string">244183135447</ref><tglBayar xsi:type="xsd:string">10-11-2017 17:28:37</tglBayar></return></ns1:ppPaymentResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>
*/
