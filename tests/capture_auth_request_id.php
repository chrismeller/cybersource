<?php

	require( dirname( __FILE__ ) . '/main.php' );

	try {
		// Pass transaction ID as string to avoid MAX_INT problems.
		$c->reference_code('1504975625');
		$c->capture( null, '5.55', '5049756376926336803008');
	}
	catch ( CyberSource_Declined_Exception $e ) {
		echo 'Transaction declined';
	}

	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

?>