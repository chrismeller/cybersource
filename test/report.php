<?php
	error_reporting(~0);
	ini_set('display_errors', 1);

	//require realpath(dirname(__FILE__) . '/../vendor/autoload.php');
	require realpath(dirname(__FILE__) . '/../classes/CyberSource/Reporting.php');
	require realpath(dirname(__FILE__) . '/config.php');

	try {

		$cr = new CyberSource\Reporting($merchant_id, $username, $password, CyberSource\Reporting::ENV_TEST);
		$cr->set_proxy($proxy);

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

// EOL