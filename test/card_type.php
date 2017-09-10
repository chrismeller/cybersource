<?php

	require( dirname( __FILE__ ) . '/main.php' );
	
	$cards = array(
		'American Express' => '378282246310005',
		'Discover' => '6011111111111117',
		'JCB' => '3566111111111113',
		'MasterCard' => '5555555555554444',
		'Visa' => '4111111111111111',
		
		// the rest of these should be unrecognized
		'Laser' => '6304985028090561515',
		'Maestro' => '50339619890917',
		'UATP' => '135412345678911',
	);

	header("Content-Type: text/plain");
	
	foreach ( $cards as $type => $number ) {
		
		$detected = $c->card_type( $number );
		
		if ( $type == $detected ) {
			echo 'Detected ' . $detected . PHP_EOL;
		}
		else if ( $detected === null ) {
			echo 'We didn\'t know what type ' . $type . ' really was.' . PHP_EOL;;
		}
		else {
			echo 'Yikes, we thought ' . $type . ' was really a ' . $detected . PHP_EOL;
		}
		
	}

// EOF