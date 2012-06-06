<?php

	require('main.php');

	$cr = new CyberSource_Reporting( $merchant_id, $username, $password, CyberSource_Reporting::ENV_PRODUCTION );
	$payments = $cr->payment_submission_detail();

	$total = 0;
	foreach ( $payments as $payment ) {
		$total = $total + $payment['amount'];
	}

	echo number_format( $total, 2 );

?>