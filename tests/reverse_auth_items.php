<?php

	// require the auth_items test to perform an authorization for a set of items
	require('auth_items.php');

	// get the request id
	$request_id = $c->response->requestID;

	// perform the reversal - note that there's no amount specified, it's using the list of items we already added in the auth process
	$c->reverse_authorization( $request_id );

	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

?>