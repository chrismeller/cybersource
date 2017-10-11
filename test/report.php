<?php

	error_reporting(0);

	//require realpath(dirname(__FILE__) . '/../vendor/autoload.php');
	require realpath(dirname(__FILE__) . '/../classes/CyberSource/Reporting.php');
	require realpath(dirname(__FILE__) . '/config.php');

	try {

		$cr = new CyberSource\Reporting($merchant_id, $username, $password, CyberSource\Reporting::ENV_TEST);
		//$cr->set_proxy($proxy);

		//$payments = $cr->payment_submission_detail(); // default yesterday
		$payments = $cr->payment_submission_detail('20171010'); /* yyyyMMdd */ 

		$currency_amount = array();

		foreach ( $payments as $payment ) {

			//print_r($payment); continue;
			$currency = $payment['currency'];

			if (! array_key_exists($currency, $currency_amount)) {
				$currency_amount[$currency] = $payment['amount'];
			}
			else {
				$currency_amount[$currency] += $payment['amount'];
			}
		}

		header("Content-Type: text/plain");
		echo '--------------' . PHP_EOL;
		echo 'PAYMENT REPORT' . PHP_EOL;
		echo '--------------' . PHP_EOL;

		foreach ($currency_amount as $currency => $amount ) {
			echo $currency . ': ' . number_format($amount, 2) . PHP_EOL;
		}

	}
	catch (Exception $e) {
		echo $e->getMessage();
	}

// EOL