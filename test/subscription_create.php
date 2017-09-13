<?php

	require realpath(dirname( __FILE__ ) . '/main.php');
	
	$c->card( '4111111111111111', '12', '2022', '123', 'Visa' )
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
	
	$c->reference_code( time() );

	try {

		$subscription = $c->create_subscription();
		echo 'Subscription ID: ' . $subscription->paySubscriptionCreateReply->subscriptionID . '<br/>' . PHP_EOL;
	}
	catch ( Exception $e ) {
		echo $e->getCode() . ': ' . $e->getMessage() . '<br/>' . PHP_EOL;
	}
	
	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

// EOL