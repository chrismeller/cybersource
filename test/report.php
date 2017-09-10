<?php

	error_reporting(0);

	require( dirname( __FILE__ ) . '/../vendor/autoload.php' );
	require( dirname( __FILE__ ) . '/config.php' );

	$cr = new CyberSource\Reporting( $merchant_id, $username, $password, CyberSource\Reporting::ENV_TEST );

	try {
		$payments = $cr->payment_submission_detail('20170908');

		$total = 0;
		foreach ( $payments as $payment ) {
			$total = $total + $payment['amount'];
		}

		header("Content-Type: text/plain");
		echo number_format( $total, 2 );
	}
	catch (Exception $e) {
		echo $e->getMessage();
	}

// EOL