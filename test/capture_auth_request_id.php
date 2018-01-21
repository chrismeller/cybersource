<?php

	require realpath(dirname( __FILE__ ) . '/main.php');

	$reference_code = $_GET['reference_code'];
	$amount         = $_GET['amount'];
	$currency       = $_GET['currency'];
	$request_id     = $_GET['request_id'];

	try {

		// Pass transaction ID as string to avoid MAX_INT problems.
		//$c->reference_code('1504975625');
		//$c->capture( null, '5.55', null, '5049756376926336803008');

		$c->reference_code($reference_code);
		$c->capture(null, $amount, $currency, $request_id);
	}
	//catch ( CyberSource_Declined_Exception $e ) {
	catch (Exception $e) {
		echo 'Transaction declined';
	}

	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

// EOL