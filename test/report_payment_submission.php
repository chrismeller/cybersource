<?php

	require realpath(dirname( __FILE__ ) . '/report_main.php');

	try {

		//$payments = $cr->payment_submission_detail(); // default yesterday
		$payments = $cr->payment_submission_detail('20171011'); /* yyyyMMdd */ 

		$total    = 0.0;

		foreach ( $payments as $payment ) {
			//print_r($payments);
			$total += $total + $payment['amount'];
		}

		header("Content-Type: text/plain");
		echo '------------------' . PHP_EOL;
		echo 'PAYMENT SUBMISSION' . PHP_EOL;
		echo '------------------' . PHP_EOL;

		echo number_format($total, 2) . PHP_EOL;

	}
	catch (Exception $e) {
		echo $e->getMessage();
	}

// EOF