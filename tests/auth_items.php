<?php

	require( dirname( __FILE__ ) . '/main.php' );
	
	$c->card( '4111111111111111', '12', '2022', '123' )
		->bill_to( array(
			'firstName' => 'John',
			'lastName' => 'Doe',
			'street1' => '123 Main Street',
			'city' => 'Columbia',
			'state' => 'SC',
			'postalCode' => '29201',
			'country' => 'US',
			'email' => 'john.doe@example.com',
			'ipAddress' => '127.0.0.1'
		) )
		->add_item( 5 )
		->add_item( 10 )
		->add_item( 1, 2 )
		->add_item( 1.25 );

	$c->reference_code( time() );
	$c->authorize(null, 'THB');
	
	
	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

// EOF