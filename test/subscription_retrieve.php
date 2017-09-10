<?php

	require( dirname( __FILE__ ) . '/main.php' );
	
	try {
		$c->reference_code('1504979191');
		$subscription = $c->retrieve_subscription('5049791964456314803010');
		//print_r($subscription);
	}
	catch ( Exception $e ) {
		echo $e->getCode() . ': ' . $e->getMessage() . '<br />';
	}
	
	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

// EOL