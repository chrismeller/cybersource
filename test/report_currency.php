<?php

	require realpath(dirname( __FILE__ ) . '/report_main.php');

	try {

		// $transactions = $cr->transaction_detail(); // /* yesterday */
		$transactions = $cr->transaction_detail('20171011'); /* yyyyMMdd */ 
		$currency_amount = array();

		foreach ( $transactions as $payment ) {

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
		echo '------------------------' . PHP_EOL;
		echo 'TOTAL AMOUNT BY CURRENCY' . PHP_EOL;
		echo '------------------------' . PHP_EOL;

		foreach ($currency_amount as $currency => $amount ) {
			if ($amount == 0) continue;
			echo $currency . ': ' . number_format($amount, 2) . PHP_EOL;
		}

	}
	catch (Exception $e) {
		echo $e->getMessage();
	}

// EOF