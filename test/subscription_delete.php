<?php

	require realpath(dirname( __FILE__ ) . '/main.php');
	
	try {
		$c->reference_code( time() );
		$c->delete_subscription('5053458480656253103012');
	}
	catch ( Exception $e ) {
		echo $e->getCode() . ': ' . $e->getMessage() . '<br />';
	}
	
	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

// EOL