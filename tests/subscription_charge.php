<?php

	require('main.php');
	
	try {
		$c->charge_subscription( '3099774717110176056428', '75' );
	}
	catch ( Exception $e ) {
		echo $e->getCode() . ': ' . $e->getMessage() . '<br />';
	}
	
	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

?>