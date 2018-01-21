<?php

error_reporting(0);

//require realpath(dirname( __FILE__ ) . '/../vendor/autoload.php');
require realpath(dirname( __FILE__ ) . '/../classes/CyberSource/CyberSource.php');
require realpath(dirname( __FILE__ ) . '/config.php');

$c = CyberSource\CyberSource::factory($merchant_id, $transaction_key, CyberSource\CyberSource::ENV_TEST);
// $c->set_proxy($proxy);

// EOF