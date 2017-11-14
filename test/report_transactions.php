<?php

	error_reporting(0);
	
	require realpath(dirname(__FILE__) . '/../classes/CyberSource/Reporting.php');
	require realpath(dirname(__FILE__) . '/config.php');

	$cr = new CyberSource\Reporting($merchant_id, $username, $password, CyberSource\Reporting::ENV_TEST);
	$cr->set_proxy($proxy);
	
	// $transactions = $cr->transaction_detail(); // /* yesterday */
	$transactions = $cr->transaction_detail('20171011'); /* yyyyMMdd */ 

	$customers = array();
	foreach ($transactions as $transaction) {
		//print_r($transactions); continue;
		$customers[] = @$transaction['customer_email'];
	}

	header("Content-Type: text/plain");
	print_r(array_filter(array_unique($customers)));

// EOL