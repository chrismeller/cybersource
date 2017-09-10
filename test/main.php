<?php

error_reporting(0);

require( dirname( __FILE__ ) . '/../vendor/autoload.php' );
//require( dirname( __FILE__ ) . '/../classes/CyberSource/CyberSource.php' );
require( dirname( __FILE__ ) . '/config.php' );

$c = CyberSource\CyberSource::factory($merchant_id, $transaction_key, CyberSource\CyberSource::ENV_TEST);

// EOF