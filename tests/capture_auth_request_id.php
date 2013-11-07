<?php

	require( dirname( __FILE__ ) . '/main.php' );

	try {
		// Pass transaction ID as string to avoid MAX_INT problems.
		$c->capture( null, '96', '323424325345255243'  );
	}
	catch ( CyberSource_Declined_Exception $e ) {
		echo 'Transaction declined';
	}

	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

?>