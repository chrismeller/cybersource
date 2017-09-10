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
			'phoneNumber' => '+662-2962000'
		) );

	$c->reference_code( time() );
	$c->device_fingerprint_id($_GET['df_id']);
	
	// $c->authorize('5.55'); // USD
	$c->authorize('5.55', 'THB');

	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

// EOL