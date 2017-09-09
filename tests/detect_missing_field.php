<?php

	require( dirname( __FILE__ ) . '/main.php' );
	
	$c->card( '4111111111111111', null, '2022', '123' )
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
		$c->authorize( 1 );
	}
	catch ( CyberSource_Missing_Field_Exception $e ) {
		echo $e->getMessage();
	}
	
	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

?>