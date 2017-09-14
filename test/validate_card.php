<?php

	require realpath(dirname( __FILE__ ) . '/main.php');
	
	$c->card( '4111111111111111', '12', '2025', '123' )
		->bill_to( array(
			'firstName' => 'John',
			'lastName' => 'Doe',
			'street1' => '123 Main Street',
			'city' => 'Columbia',
			'state' => 'SC',
			'postalCode' => '29201',
			'country' => 'US',
			'email' => 'john.doe@example.com',
		) );

	$c->reference_code('V' . time() );
	$c->validate_card();
	
	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

// EOF