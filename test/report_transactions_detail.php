<?php

	require realpath(dirname( __FILE__ ) . '/report_main.php');
	
	// $transactions = $cr->transaction_detail(); /* yesterday */
	$transactions = $cr->transaction_detail('20171108'); /* yyyyMMdd */ 

	header("Content-Type: text/plain");

	foreach ($transactions as $txn) {
		echo $txn['transaction_date'] . "\t" . $txn['merchant_ref_number'] . "\t" .
		     $txn['currency'] . ' ' . $txn['amount'] . "\t" . $txn['source'] . PHP_EOL;
	}

	//print_r($transactions) . PHP_EOL;
	print_r(json_encode($transactions, JSON_PRETTY_PRINT)) . PHP_EOL;

// EOF