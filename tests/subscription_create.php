<?php

	require('main.php');
	
	$c->card( '4111111111111111', '12', '2013', '123', 'Visa' )
		->bill_to( array(
			'firstName' => 'John',
			'lastName' => 'Tester',
			'street1' => '123 Main Street',
			'city' => 'Columbia',
			'state' => 'SC',
			'postalCode' => '29201',
			'country' => 'US',
			'email' => 'john.tester@example.com',
		) );
	
	try {
		$subscription_id = $c->create_subscription();
		
		echo 'Subscription ID: ' . $subscription_id . '<br />';
	}
	catch ( Exception $e ) {
		echo $e->getCode() . ': ' . $e->getMessage() . '<br />';
	}
	
	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

?>