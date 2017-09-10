<?php

	error_reporting(0);
	
	require( dirname( __FILE__ ) . '/../vendor/autoload.php' );
	require( dirname( __FILE__ ) . '/config.php' );

	$cr = new CyberSource\Reporting( $merchant_id, $username, $password, CyberSource\Reporting::ENV_TEST );
	$transactions = $cr->transaction_detail(/* yyyyMMdd */ '20170908'); 

	$customers = array();
	foreach ($transactions as $transaction) {
		$customers[] = @$transaction['customer_email'];
	}

	header("Content-Type: text/plain");
	print_r( array_filter( array_unique( $customers ) ) );

// EOL