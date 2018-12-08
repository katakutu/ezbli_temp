<?php

require_once 'connect.class.php';

$class	= new bbuzzPaymentPoint();

// Inquiry Telkom PSTN 1 Tagihan
$inquiry = $class->inquiry('TELKOMPSTN', '0313282370');
$payment = $class->payment('TELKOMPSTN', $inquiry['refID'], $inquiry['totalTagihan']);

print_r( $inquiry );
echo "\n";
print_r( $payment );