<?php

	require realpath(dirname( __FILE__ ) . '/report_main.php');
	
	// $transactions = $cr->transaction_detail(); // /* yesterday */
	$transactions = $cr->transaction_detail('20171011'); /* yyyyMMdd */ 

	$customers = array();
	foreach ($transactions as $transaction) {
		//print_r($transactions); continue;
		$customers[] = @$transaction['customer_email'];
	}

	header("Content-Type: text/plain");
	print_r(array_filter(array_unique($customers)));

// EOF