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
	<div class="col-sm-8 col-md-8 col-lg-6">
	    <div class="row">
	    	<h1>CyberSource SOAP Toolkit API </h1>
	    	<h3>EBC Test</h3>
	    	<ul>
				<li><a href="https://ebctest.cybersource.com/ebctest/login/Login.do" target="ebc-test">Login</a></li>
			</ul>

		    <h3>Auth / Sale / Void / Refund</h3>
<pre>
  State            Action
  ======================================================
  Authorized   =>  Capture (Settlement)
               =>  Reverse (Full Authorization Reversal)
  ------------------------------------------------------
  Settled      =>  Void
  ------------------------------------------------------
  Transmitted  =>  Credit (Refund *)
  ------------------------------------------------------
</pre>
		    <ul>
				<li><a href="auth_amount.php">auth_amount</a></li>
				<li><a href="auth_items.php">auth_items</a></li>
				<li><a href="capture_auth_amount.php">capture_auth_amount</a></li>
				<li><a href="capture_auth_items.php">capture_auth_items</a></li>
				<li><a href="capture_auth_request_id.php">capture_auth_request_id</a></li>
				<li><a href="charge.php">charge</a></li>
				<li><a href="void.php">void</a></li>
				<li><a href="credit.php">credit</a></li>
				<li><a href="reverse_auth_amount.php">reverse_auth_amount</a></li>
				<li><a href="reverse_auth_items.php">reverse_auth_items</a></li>
				<li><a href="device_fingerprint_form.php">device_fingerprint</a></li>
				<li><a href="validate_card.php">validate_card</a></li>
				<li><a href="detect_invalid_field.php">detect_invalid_field</a></li>
				<li><a href="detect_missing_field.php">detect_missing_field</a></li>
				<li><a href="card_type.php">card_type</a></li>
				<li><a href="extending_error_logic.php">extending_error_logic</a></li>
				<li><a href="extended_example.php">extended_example</a> ***</li>
		    </ul>

		    <h3>Payment Tokenization</h3>
		    <ul>
		    	<li><a href="subscription_create.php">subscription_create</a></li>
				<li><a href="subscription_charge.php">subscription_charge</a></li>
				<li><a href="subscription_credit.php">subscription_credit</a></li>
				<li><a href="subscription_retrieve.php">subscription_retrieve</a></li>
				<li><a href="subscription_update.php">subscription_update</a></li>
				<li><a href="subscription_delete.php">subscription_delete</a></li>
		    </ul>

		    <h3>Recurring Billing</h3>
		    <ul>
		    	<li><a href="subscription_recurring.php">Recurring</a></li>
		    	<li><a href="subscription_installment.php">Installment</a></li>
		    </ul>

		    <h3>Report Inquiry</h3>
		    <ul>
		    	<li><a href="report.php">report</a></li>
				<li><a href="report_transactions.php">report_transactions</a></li>
				<li><a href="report_transactions_detail.php">report_transactions_detail</a></li>
		    </ul>

		    <h3>Payer Authen (3-D Secure)</h3>
		    <ul>
		    	<li><a href="PayerAuthEnroll.php">PayerAuthEnroll</a></li>
		    	<li><a href="PayerAuthValidate.php">PayerAuthValidate</a></li>
		    </ul>

		    <h3>Decision Management</h3>
		    <ul>
		    	<li><a href="DMAuthWeb.php">DMAuthWeb</a></li>
		    	<li><a href="DMAuthApi.php">DMAuthApi</a></li>
		    	<li><a href="DMAuthNVP.php">DMAuthNVP</a></li>
		    </ul>
	    </div>
	</div>
</div>

<br/>
<br/>

<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>

</script>
</body>
</html>

