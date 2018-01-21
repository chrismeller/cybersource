<?php

	require realpath(dirname(__FILE__) . '/main.php');

	$request_id = '5053113424856675003008 ';
	$amount     = '5.55';
	$currency   = 'THB';
	$reference_code = 'C' . time();

	try {
		// Pass transaction ID as string to avoid MAX_INT problems.
		$c->reference_code($reference_code);
		$c->credit($request_id, $amount, $currency);
	}
	catch (CyberSource_Declined_Exception $e) {
		echo 'Transaction declined';
	}

	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

// EOL