<?php

	// require the auth_amount test to perform an authorization for a given amount
	require('auth_amount.php');

	// get the amount and request id
	$request_id = $c->response->requestID;
	$amount     = $c->response->ccAuthReply->amount;

	// perform the reversal
	$c->reverse_authorization( $request_id, $amount );

	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

// EOL