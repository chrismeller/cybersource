<?php

	require( dirname( __FILE__ ) . '/../vendor/autoload.php' );
	require( dirname( __FILE__ ) . '/config.php' );

	$cr = new CyberSource\Reporting( $merchant_id, $username, $password, CyberSource\Reporting::ENV_TEST );
	$transactions = $cr->transaction_detail();

	$customers = array();
	foreach ($transactions as $transaction) {
		$customers[] = @$transaction['customer_email'];
	}

	print_r( array_filter( array_unique( $customers ) ) );

// EOL