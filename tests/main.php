<?php

	require( dirname( __FILE__ ) . '/../vendor/autoload.php' );
	require( dirname( __FILE__ ) . '/config.php' );

	$c = CyberSource\CyberSource::factory( $merchant_id, $transaction_id, CyberSource\CyberSource::ENV_TEST );

?>