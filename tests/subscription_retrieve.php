<?php

	require( dirname( __FILE__ ) . '/main.php' );
	
	try {
		$subscription = $c->retrieve_subscription( '3099774717110176056428' );
	}
	catch ( Exception $e ) {
		echo $e->getCode() . ': ' . $e->getMessage() . '<br />';
	}
	
	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

?>