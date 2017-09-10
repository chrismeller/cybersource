<?php

	require( dirname( __FILE__ ) . '/main.php' );
	
	try {
		$c->reference_code( time() );
		$c->charge_subscription('5049768459176396103012', '75');
	}
	catch ( Exception $e ) {
		echo $e->getCode() . ': ' . $e->getMessage() . '<br />';
	}
	
	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

// EOL