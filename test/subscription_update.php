<?php

	/*

	update_subscription
	  => cannot update card information
      => only update billing information

	*/

	require realpath(dirname( __FILE__ ) . '/main.php');
	
	//$c->card( '4111111111111111', '12', '2025', '123', 'Visa' );
	$c->bill_to( array(
		'firstName' => 'John',
		'lastName' => 'Doe',
		'street1' => '123 Main Street',
		'city' => 'Columbia',
		'state' => 'SC',
		'postalCode' => '29201',
		'country' => 'US',
		'email' => 'john.doe@example.com',
	));
	
	try {
		$subscription_id = '5053355694176713403008';
		$c->reference_code($subscription_id);
		$c->update_subscription($subscription_id);
	}
	catch ( Exception $e ) {
		echo $e->getCode() . ': ' . $e->getMessage() . '<br/>' . PHP_EOL;
	}
	
	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

// EOL