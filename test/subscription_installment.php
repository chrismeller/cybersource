<?php

	require realpath(dirname( __FILE__ ) . '/main.php');
	
	$c->card( '4111111111111111', '12', '2022', '123', 'Visa' );

	$c->bill_to( array(
		'firstName'  => 'John',
		'lastName'   => 'Doe',
		'street1'    => '123 Main Street',
		'city'       => 'Columbia',
		'state'      => 'SC',
		'postalCode' => '29201',
		'country'    => 'US',
		'email'      => 'john.doer@example.com',
		'ipAddress'  => '10.7.7.7'
	) );

	$tomorrow = new DateTime('tomorrow');

	$c->recurring( array(
		'frequency'        => 'monthly',
		'amount'           => '199.00',
		'currency'         => 'THB',
		'startDate'        => $tomorrow->format('Ymd'),
		'installment'      => 'true',
		'automaticRenew'   => 'true',
		'numberOfPayments' => '6'
	));

	header("Content-Type: text/plain");

	try {

		$c->reference_code( time() );
		$subscription = $c->recurring_subscription();

	}
	catch ( Exception $e ) {
		echo $e->getCode() . ': ' . $e->getMessage() . '<br/>' . PHP_EOL;
	}
	
	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

// EOL