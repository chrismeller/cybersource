<?php

	require('main.php');
	
	$c->card( '4111111111111111', '12', '2013', '123' )
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
	
	$auth_response = $c->authorize( '5.55' );
	
	if ( !isset( $auth_response->requestToken ) ) {
		die('Authorization seems to have failed!');
	}
	
	try {
		$c->capture( $auth_response->requestToken, '5' );
	}
	catch ( CyberSource_Declined_Exception $e ) {
		echo 'Transaction declined';
	}
	
	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

?>