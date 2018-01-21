<?php

	error_reporting(0);
	
	require realpath(dirname(__FILE__) . '/../classes/CyberSource/Reporting.php');
	require realpath(dirname(__FILE__) . '/config.php');

	$cr = new CyberSource\Reporting($merchant_id, $username, $password, CyberSource\Reporting::ENV_TEST);
	//$cr->set_proxy($proxy);

// EOF