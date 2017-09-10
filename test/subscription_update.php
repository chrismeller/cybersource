<?php

	/*

	update_subscription
	  => cannot update card information
      => only update billing information

	*/

	require( dirname( __FILE__ ) . '/main.php' );
	
	$c->card( '4111111111111111', '12', '2025', '123', 'Visa' )
		->bill_to( array(
			'firstName' => 'John',
			'lastName' => 'Doe',
			'street1' => '321 Main Street',
			'city' => 'Columbia',
			'state' => 'SC',
			'postalCode' => '29201',
			'country' => 'US',
			'email' => 'john.doe@example.com',
		) );
	
	try {
		$c->reference_code('1504979191');
		$c->update_subscription('5049791964456314803010');
	}
	catch ( Exception $e ) {
		echo $e->getCode() . ': ' . $e->getMessage() . '<br/>' . PHP_EOL;
	}
	
	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

// EOL