<?php

require realpath(dirname( __FILE__ ) . '/config.php');

define ('QUERY_URL_TEST', 'https://ebctest.cybersource.com/ebctest/Query');
define ('QUERY_URL_LIVE', 'https://ebc.cybersource.com/ebc/Query');

ob_start();

//---------------------------------------------------------------------------//

//$type = 'json';
$type                    = @$_GET['type'];
$requestID               = @$_GET['requestID'];
$merchantReferenceNumber = @$_GET['merchantReferenceNumber'];
$targetDate              = @$_GET['targetDate'];

//---------------------------------------------------------------------------//

$authz = $username . ':' . $password;
//echo $authz . PHP_EOL;

$authz = base64_encode($authz);
//echo $authz . PHP_EOL;

$conn = curl_init();
curl_setopt($conn, CURLOPT_URL, QUERY_URL_TEST);
curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, 0);

curl_setopt($conn, CURLOPT_POST, 1);
$post_fields  = 'merchantID=' . $merchant_id;
$post_fields .= '&type=transaction';
$post_fields .= '&subtype=transactionDetail';
$post_fields .= '&subtype=transactionDetail';
$post_fields .= '&versionNumber=1.7';

// single result
if (!empty($requestID)) {
	$post_fields .= '&requestID=' . $requestID;
}

// step result
elseif (!empty($merchantReferenceNumber) && !empty($targetDate)) {
	$post_fields .= '&merchantReferenceNumber=' . $merchantReferenceNumber;
	$post_fields .= '&targetDate=' . $targetDate;
}
else {
	die('invalid query parameters');
}

// print_r($post_fields);
curl_setopt($conn, CURLOPT_POSTFIELDS, $post_fields);

//ADD header array
$headers = array('Content-type: application/x-www-form-urlencoded'
	           , 'Authorization: Basic ' . $authz);

curl_setopt($conn, CURLOPT_HTTPHEADER, $headers);

//RETURN
curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($conn);

$http_code = curl_getinfo($conn, CURLINFO_HTTP_CODE);
// echo 'HTTP CODE: ' . $http_code . PHP_EOL;

//Check error
if (curl_error($conn)) {
	echo 'error:' . curl_error($conn);
}
else {

	ob_end_clean();
	$content_type = 'text/xml';

	if ($type === 'json') {
		$content_type = 'application/json';
		$result = json_encode(simplexml_load_string($result), JSON_PRETTY_PRINT);
	}

	header('Content-Type: ' . $content_type);
	echo $result;
}

//Close connect
curl_close($conn);

// EOF
