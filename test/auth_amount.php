<?php

	require( dirname( __FILE__ ) . '/main.php' );

	$c->card( '4111111111111111', '12', '2022', '123' )
		->bill_to( array(
			'firstName' => 'John',
			'lastName' => 'Doe',
			'street1' => '123 Main Street',
			'street2' => 'Apple Building',
			'city' => 'Columbia',
			'state' => 'SC',
			'postalCode' => '29201',
			'country' => 'US',
			'email' => 'john.doe@example.com',
		) );

	$c->reference_code( time() );
	
	// $c->authorize('5.55'); // USD
	$c->authorize('5.55', 'THB');

	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

	$reference_code = $c->response->merchantReferenceCode;
	$amount         = $c->response->ccAuthReply->amount;
	$currency       = $c->response->purchaseTotals->currency;
	$request_id     = $c->response->requestID;

	$link = "capture_auth_request_id.php"
	      . "?reference_code=" . $reference_code
	      . "&amount=" . $amount
	      . "&currency=" . $currency
	      . "&request_id=" . $request_id;

?>

<a href="<?php echo $link ?>">capture_auth_request_id</a>
