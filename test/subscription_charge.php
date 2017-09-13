<?php

	require realpath(dirname( __FILE__ ) . '/main.php');
	
	try {
		$c->reference_code( time() );
		$c->charge_subscription('5053355694176713403008', '75', 'THB');
	}
	catch ( Exception $e ) {
		echo $e->getCode() . ': ' . $e->getMessage() . '<br />';
	}
	
	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

// EOL