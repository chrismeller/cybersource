<?php

	// require the charge test, which charges a number of items that we'll then void
	require('charge.php');

	// get the request id
	$request_id = $c->response->requestID;

	// perform the void
	$c->void( $request_id );

	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

// EOL