<?php

require( dirname( __FILE__ ) . '/config.php' );

// DF TEST: 1snn5n9w, LIVE: k8vif92e 
define('DF_ORG_ID', '1snn5n9w');

session_start();
$sess_id  = session_id();
$df_param = 'org_id=' . DF_ORG_ID . '&amp;session_id=' . $merchant_id . $sess_id;

?>
<html lang="th">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CyberSource SOAP PHP</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css">
<!-- E8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.2/html5shiv.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.2.0/respond.js"></script>
<![endif]-->

<style type="text/css">

}

</style>

</head>

<body>

<div class="container">
	<div class="col-sm-8 col-md-8 col-lg-8">
	    <div class="row">
	    	<h1>CyberSource SOAP PHP</h1>
	    	<h3>Device Fingerprint ID</h3>
	    	<ul>
	    		<li>device_fingerprint_param: <?php echo $df_param ?></li>
				<li><a href="device_fingerprint.php?df_id=<?php echo $sess_id ?>">Authorize + Device Fingerprint</a></li>
			</ul>
	    </div>
	</div>
</div>

<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>

</script>

<!-- DF START -->
<p style="background:url(https://h.online-metrix.net/fp/clear.png?<?php echo $df_param ?>&amp;m=1)"></p>
<img src="https://h.online-metrix.net/fp/clear.png?<?php echo $df_param ?>&amp;m=2" width="1" height="1" />
<!-- DF END -->

</body>
</html>

