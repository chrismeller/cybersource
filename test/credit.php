<?php

	require realpath(dirname( __FILE__ ) . '/charge.php');

	$request_id = $c->response->requestID;
	$amount     = $c->response->ccCaptureReply->amount;
	$currency   = $c->response->purchaseTotals->currency;
	$reference_code = $c->response->merchantReferenceCode;

	try {
		// Pass transaction ID as string to avoid MAX_INT problems.
		$c->reference_code($reference_code);
		$c->credit($request_id, $amount, $currency);
	}
	catch ( CyberSource_Declined_Exception $e ) {
		echo 'Transaction declined';
	}

	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

// EOL