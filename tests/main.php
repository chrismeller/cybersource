<?php

	require('../cybersource.php');
	require('../cybersource_reporting.php');

	require('config.php');

	$c = CyberSource::factory( $merchant_id, $transaction_id, CyberSource::ENV_TEST );

?>