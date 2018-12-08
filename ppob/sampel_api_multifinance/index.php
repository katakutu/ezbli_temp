<?php

/*
 ***********************************************************************
 List of Product Code
 ***********************************************************************
 ┌──────────────────┬──────────────────────────────────────────────────┐
 │ Code 			│ Description									   │
 ├──────────────────┼──────────────────────────────────────────────────┤
 │ FNFIF            │ FIF Finance                                      │
 │ FNWOMD           │ Wahana Ottomitra Multiartha Finance              │
 │ MEGAFIND			│ Mega Finance							   		   │
 │ FNCOLUMD			│ Columbia Finance								   │
 │ FNBAFD			│ Bussan Auto Finance							   │
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
Sample for FIF Finance Konvensional
-----------------------------------------------------------------------
*/

// Inquiry FIF Finance
$fifInquiry = $helper->inquiry($productCode = 'FNFIF', $idpel = '101000114217');

// Response Inquiry
/*
Array
(
    [responseCode] => 00
    [message] => 
    [nomorKontrak] => 101000114217
    [nama] => SURYANI NANI
    [angsuranKe] => 005
    [totalAngsuran] => 011
    [jatuhTempo] => 02/07/2017
    [platform] => K
    [angsuran] => 4025905
    [denda] => 75000
    [biayaTagih] => 0
    [admin] => 2000
    [totalBayar] => 4102905
    [fee] => 2100
    [productCode] => FNFIF
    [refID] => 4443186
)
*/

// Payment FIF Finance
$fifPayment = $helper->payment($productCode = 'FNFIF', $refID = $fifInquiry['refID'], $nominal = $fifInquiry['totalBayar']);

// Response Payment
/*
Array
(
    [responseCode] => 00
    [message] => 
    [nomorKontrak] => 101000114217
    [nama] => SURYANI NANI
    [angsuranKe] => 005
    [totalAngsuran] => 011
    [jatuhTempo] => 02/07/2017
    [platform] => K
    [noReferensi] => 001394
    [angsuran] => 4025905
    [denda] => 75000
    [biayaTagih] => 0
    [admin] => 2000
    [totalBayar] => 4102905
    [fee] => 2100
)
*/

print_r($fifInquiry);
echo "\n\n";

print_r($fifPayment);
echo "\n\n";


/*
-----------------------------------------------------------------------
Sample for FIF Finance Syari'ah
-----------------------------------------------------------------------
*/

// Inquiry FIF Finance
$fifInquiry = $helper->inquiry($productCode = 'FNFIF', $idpel = '101900009917');

// Response Inquiry
/*
Array
(
    [responseCode] => 00
    [message] => 
    [nomorKontrak] => 101900009917
    [nama] => CHRISTINA LINNAWATI
    [angsuranKe] => 008
    [totalAngsuran] => 017
    [jatuhTempo] => 04/09/2017
    [platform] => S
    [angsuran] => 4436000
    [denda] => 0
    [biayaTagih] => 5000
    [admin] => 2000
    [totalBayar] => 4443000
    [fee] => 2100
    [productCode] => FNFIF
    [refID] => 4443192
)
*/

// Payment FIF Finance
$fifPayment = $helper->payment($productCode = 'FNFIF', $refID = $fifInquiry['refID'], $nominal = $fifInquiry['totalBayar']);

// Response Payment
/*
Array
(
    [responseCode] => 00
    [message] => 
    [nomorKontrak] => 101900009917
    [nama] => CHRISTINA LINNAWATI
    [angsuranKe] => 008
    [totalAngsuran] => 017
    [jatuhTempo] => 04/09/2017
    [platform] => S
    [noReferensi] => 001393
    [angsuran] => 4436000
    [denda] => 0
    [biayaTagih] => 5000
    [admin] => 2000
    [totalBayar] => 4443000
    [fee] => 2100
)
*/

print_r($fifInquiry);
echo "\n\n";

print_r($fifPayment);
echo "\n\n";


/*
-----------------------------------------------------------------------
Sample for FIF Finance Angsuran Terakhir
-----------------------------------------------------------------------
*/

// Inquiry FIF Finance
$fifInquiry = $helper->inquiry($productCode = 'FNFIF', $idpel = '101900009917');

// Response Inquiry
/*
Array
(
    [responseCode] => 00
    [message] => 
    [nomorKontrak] => 816900283315
    [nama] => DADANG SETIAWAN
    [angsuranKe] => 028
    [totalAngsuran] => 033
    [jatuhTempo] => 15/09/2017
    [platform] => S
    [angsuran] => 2240000
    [denda] => 0
    [biayaTagih] => 5000
    [admin] => 2000
    [totalBayar] => 2247000
    [fee] => 2100
    [productCode] => FNFIF
    [refID] => 4443203
)

*/

// Payment FIF Finance
$fifPayment = $helper->payment($productCode = 'FNFIF', $refID = $fifInquiry['refID'], $nominal = $fifInquiry['totalBayar']);

// Response Payment
/*
Array
(
    [responseCode] => 92
    [message] => ANGSURAN TERAKHIR,|  SILAHKAN MEMBAYAR DIKANTOR CABANG FIF     
)

*/

print_r($fifInquiry);
echo "\n\n";

print_r($fifPayment);
echo "\n\n";


/*
-----------------------------------------------------------------------
Sample for WOM Finance
-----------------------------------------------------------------------
*/

// Inquiry WOM Finance
$womInquiry = $helper->inquiry($productCode = 'FNWOMD', $idpel = '801800032048');

// Response Inquiry
/*
Array
(
    [responseCode] => 00
    [message] => SUCCESS
    [nomorKontrak] => 801800032048
    [nama] => EKO DJATMIKO  NINGSI
    [jatuhTempo] => 20082017  
    [angsuranKe] => 19 
    [tagihan] => 530000
    [admin] => 0
    [totalBayar] => 530000
    [fee] => 1300
    [productCode] => FNWOMD
    [refID] => 3571900
)
*/

// Payment WOM Finance
$womPayment = $helper->payment($productCode = 'FNWOMD', $refID = $womInquiry['refID'], $nominal = $womInquiry['totalBayar']);

// Response Payment
/*
Array
(
    [responseCode] => 00
    [message] => 
    [nomorKontrak] => 801800032048
    [nama] => EKO DJATMIKO  NINGSI
    [jatuhTempo] => 20082017  
    [angsuranKe] => 19 
    [ref] => 0150327630503745046             
    [tagihan] => 530000
    [admin] => 0
    [totalBayar] => 530000
    [fee] => 1300
)
*/

print_r($womInquiry);
echo "\n\n";

print_r($womPayment);
echo "\n\n";


/*
-----------------------------------------------------------------------
Sample for Mega Finance
-----------------------------------------------------------------------
*/

// Inquiry Mega Finance
$mcfInquiry = $helper->inquiry($productCode = 'MEGAFIND', $idpel = '1731600538');

// Response Inquiry
/*
Array
(
    [responseCode] => 00
    [message] => SUCCESS
    [nomorKontrak] => 1731600538
    [nama] => ASSHOFA ILMAN NAFIAH
    [angsuranKe] => 4/12 
    [tagihan] => 201000
    [denda] => 0
    [admin] => 0
    [totalBayar] => 201000
    [fee] => 1300
    [productCode] => MEGAFIND
    [refID] => 3572158
)
*/

// Payment Mega Finance
$mcfPayment = $helper->payment($productCode = 'MEGAFIND', $refID = $mcfInquiry['refID'], $nominal = $mcfInquiry['totalBayar']);

// Response Payment
/*
Array
(
    [responseCode] => 00
    [message] => 
    [nomorKontrak] => 1731600538
    [nama] => ASSHOFA ILMAN NAFIAH
    [angsuranKe] => 4/12 
    [ref] => 2893290                         
    [tagihan] => 201000
    [denda] => 0
    [admin] => 0
    [totalBayar] => 201000
    [info] => 
    [fee] => 1300
)
*/

print_r($mcfInquiry);
echo "\n\n";

print_r($mcfPayment);
echo "\n\n";


/*
-----------------------------------------------------------------------
Sample for Columbia Finance
-----------------------------------------------------------------------
*/

// Inquiry Columbia Finance
$columbiaInquiry = $helper->inquiry($productCode = 'FNCOLUMD', $idpel = '3001042049001');

// Response Inquiry
/*
Array
(
    [responseCode] => 00
    [message] => SUCCESS
    [nomorKontrak] => 3001042049001
    [nama] => MOH IBNU ABAS / NUR KHASANAH
    [jatuhTempo] => 08/04/2015
    [angsuranKe] => 6  
    [tagihan] => 270000
    [admin] => 0
    [totalBayar] => 270000
    [fee] => 1550
    [productCode] => FNCOLUMD
    [refID] => 3571772
)
*/

// Payment Columbia Finance
$columbiaPayment = $helper->payment($productCode = 'FNCOLUMD', $refID = $columbiaInquiry['refID'], $nominal = $columbiaInquiry['totalBayar']);

// Response Payment
/*
Array
(
    [responseCode] => 00
    [message] => 
    [nomorKontrak] => 3001042049001
    [nama] => MOH IBNU ABAS / NUR KHASANAH
    [jatuhTempo] => 08/04/2015
    [angsuranKe] => 6  
    [ref] => 000000000000
    [tagihan] => 270000
    [admin] => 0
    [totalBayar] => 270000
    [fee] => 1550
)
*/

print_r($columbiaInquiry);
echo "\n\n";

print_r($columbiaPayment);
echo "\n\n";

/*
-----------------------------------------------------------------------
Sample for Bussan Auto Finance
-----------------------------------------------------------------------
*/

// Inquiry BAF
$bafInquiry = $helper->inquiry($productCode = 'FNBAFD', $idpel = '315010008575');

// Response Inquiry
/*
Array
(
    [responseCode] => 00
    [message] => SUCCESS
    [nomorKontrak] => 315010008575
    [nama] => MARIANA YUNITA
    [jatuhTempo] => 01/06/2017
    [angsuranKe] => 012
    [tagihan] => 581700
    [admin] => 0
    [totalBayar] => 581700
    [fee] => 1300
    [productCode] => FNBAFD
    [refID] => 3571875
)
*/

// Payment BAF
$bafPayment = $helper->payment($productCode = 'FNBAFD', $refID = $bafInquiry['refID'], $nominal = $bafInquiry['totalBayar']);

// Response Payment
/*
Array
(
    [responseCode] => 00
    [message] => 
    [nomorKontrak] => 315010008575
    [nama] => MARIANA YUNITA
    [angsuranKe] => 012
    [ref] => 149982519121394032              
    [tagihan] => 581700
    [admin] => 0
    [totalBayar] => 581700
    [fee] => 1300
)
*/

print_r($bafInquiry);
echo "\n\n";

print_r($bafPayment);
echo "\n\n";



// Log Payment
$log = $helper->options($cmd = 'getPaymentData', $data = $womInquiry['refID']);

// Response Log
/*
Array
(
    [responseCode] => 00
    [message] => 
    [nomorKontrak] => 801800032048
    [nama] => EKO DJATMIKO  NINGSI
    [jatuhTempo] => 20082017  
    [angsuranKe] => 19 
    [ref] => 0150327630503745046             
    [tagihan] => 530000
    [admin] => 0
    [totalBayar] => 530000
    [fee] => 1300
)
*/


/*
 ╔══════════════════════════════════════════════════════════════════════╗
 ║ Sample PHP-SOAP XML HTTP Post Request								║
 ╚══════════════════════════════════════════════════════════════════════╝
 */

$endpoint = 'http://117.102.64.238:1212/pp/index.php';


/*
-----------------------------------------------------------------------
Sample for WOM Finance
-----------------------------------------------------------------------
*/

// Inquiry WOM Finance

// Set Inquiry Header
$womInquiryHeader = array(
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
 │ idPel		│ String │ Numeric		│ Contract Number			│
 │ idPel2		│ String │ Null 		│ 							│
 │ miscData		│ String │ Null 		│							│
 └──────────────┴────────┴──────────────┴───────────────────────────┘
 */


$womInquiryFields = '<?xml version="1.0" encoding="ISO-8859-1"?><SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tns="urn:Bakoelbuzz"><SOAP-ENV:Body><tns:ppInquiry xmlns:tns="urn:Bakoelbuzz"><productCode xsi:type="xsd:string">FNWOMD</productCode><idPel xsi:type="xsd:string">801800032048</idPel><idPel2 xsi:type="xsd:string"></idPel2><miscData xsi:type="xsd:string"></miscData></tns:ppInquiry></SOAP-ENV:Body></SOAP-ENV:Envelope>';

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $womInquiryHeader);
curl_setopt($ch, CURLOPT_POSTFIELDS, $womInquiryFields);

//execute post
$womResult = curl_exec($ch);

//close connection
curl_close($ch);

// Response Inquiry
/*
<?xml version="1.0" encoding="UTF-8"?><SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/"><SOAP-ENV:Body><ns1:ppInquiryResponse xmlns:ns1="urn:Bakoelbuzz"><return><responseCode xsi:type="xsd:string">00</responseCode><message xsi:type="xsd:string">SUCCESS</message><nomorKontrak xsi:type="xsd:string">801800032048</nomorKontrak><nama xsi:type="xsd:string">EKO DJATMIKO  NINGSI</nama><jatuhTempo xsi:type="xsd:string">20082017  </jatuhTempo><angsuranKe xsi:type="xsd:string">19 </angsuranKe><tagihan xsi:type="xsd:string">530000</tagihan><admin xsi:type="xsd:int">0</admin><totalBayar xsi:type="xsd:int">530000</totalBayar><fee xsi:type="xsd:int">1300</fee><productCode xsi:type="xsd:string">FNWOMD</productCode><refID xsi:type="xsd:string">3572611</refID></return></ns1:ppInquiryResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>
*/

echo "$womResult \n\n";



// Payment WOM Finance

// Set Payment Header
$womPaymentHeader = array(
	'Content-Type: text/xml; charset=ISO-8859-1',
	'SOAPAction: urn:ppPayment#ppPayment',
	'Authorization: Basic YmQ4NDQ2ZWFhNTc0YWIwMGFiNzIyNDlmOGIwNmE3NTk6YTMzNTU5MjA0NDRmYWU4ZWI4NGIxZjYzMDRiYmUxZDk='
);

$womPaymentFields = '<?xml version="1.0" encoding="ISO-8859-1"?><SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tns="urn:Bakoelbuzz"><SOAP-ENV:Body><tns:ppPayment xmlns:tns="urn:Bakoelbuzz"><productCode xsi:type="xsd:string">FNWOMD</productCode><refID xsi:type="xsd:string">3572611</refID><nominal xsi:type="xsd:string">530000</nominal><miscData xsi:type="xsd:string"></miscData></tns:ppPayment></SOAP-ENV:Body></SOAP-ENV:Envelope>';

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $womPaymentHeader);
curl_setopt($ch, CURLOPT_POSTFIELDS, $womPaymentFields);

//execute post
$womResult = curl_exec($ch);

//close connection
curl_close($ch);

// Response Inquiry
/*
<?xml version="1.0" encoding="UTF-8"?><SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/"><SOAP-ENV:Body><ns1:ppPaymentResponse xmlns:ns1="urn:Bakoelbuzz"><return><responseCode xsi:type="xsd:string">00</responseCode><message xsi:type="xsd:string"></message><nomorKontrak xsi:type="xsd:string">801800032048</nomorKontrak><nama xsi:type="xsd:string">EKO DJATMIKO  NINGSI</nama><jatuhTempo xsi:type="xsd:string">20082017  </jatuhTempo><angsuranKe xsi:type="xsd:string">19 </angsuranKe><ref xsi:type="xsd:string">0150327630503745046             </ref><tagihan xsi:type="xsd:string">530000</tagihan><admin xsi:type="xsd:int">0</admin><totalBayar xsi:type="xsd:int">530000</totalBayar><fee xsi:type="xsd:int">1300</fee></return></ns1:ppPaymentResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>
*/

echo "$womResult \n\n";