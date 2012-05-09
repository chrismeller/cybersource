<?php

	class CyberSource {
		
		const ENV_TEST = 'https://ics2wstest.ic3.com/commerce/1.x/transactionProcessor/CyberSourceTransaction_1.67.wsdl';
		const ENV_PRODUCTION = 'https://ics2ws.ic3.com/commerce/1.x/transactionProcessor/CyberSourceTransaction_1.67.wsdl';
		
		const VERSION = '0.2';
		const API_VERSION = '1.67';
		
		/**
		 * @var string The URL to the WSDL endpoint for the environment we're running in (test or production), as stored in self::ENV_* constants.
		 */
		public $environment = self::ENV_TEST;
		
		public $merchant_id;
		public $transaction_id;
		public $reference_code = 'Unknown';		// for backend transaction reporting
		
		public $bill_to = array();
		public $card = array();
		
		public $items = array();
		
		/**
		 * @var stdClass The generated SOAP request, saved immediately before a transaction is run.
		 */
		public $request;
		
		/**
		 * @var stdClass The retrieved SOAP response, saved immediately after a transaction is run.
		 */
		public $response;
		
		/**
		 * @var float The amount of time in seconds to wait for both a connection and a response. Total potential wait time is this value times 2 (connection + response).
		 */
		public $timeout = 10;
		
		public $avs_codes = array(
			'A' => 'Partial match: Street address matches, but 5-digit and 9-digit postal codes do not match.',
			'B' => 'Partial match: Street address matches, but postal code is not verified.',
			'C' => 'No match: Street address and postal code do not match.',
			'D' => 'Match: Street address and postal code match.',
			'E' => 'Invalid: AVS data is invalid or AVS is not allowed for this card type.',
			'F' => 'Partial match: Card member\'s name does not match, but billing postal code matches.',
			'G' => 'Not supported: Non-U.S. issuing bank does not support AVS.',
			'H' => 'Partial match: Card member\'s name does not match, but street address and postal code match.',
			'I' => 'No match: Address not verified.',
			'K' => 'Partial match: Card member\'s name matches, but billing address and billing postal code do not match.',
			'L' => 'Partial match: Card member\'s name and billing postal code match, but billing address does not match.',
			'M' => 'Match: Street address and postal code match.',
			'N' => 'No match: Card member\'s name, street address, or postal code do not match.',
			'O' => 'Partial match: Card member\'s name and billing address match, but billing postal code does not match.',
			'P' => 'Partial match: Postal code matches, but street address not verified.',
			'R' => 'System unavailable.',
			'S' => 'Not supported: U.S. issuing bank does not support AVS.',
			'T' => 'Partial match: Card member\'s name does not match, but street address matches.',
			'U' => 'System unavailable: Address information is unavailable because either the U.S. bank does not support non-U.S. AVS or AVS in a U.S. bank is not functioning properly.',
			'V' => 'Match: Card member\'s name, billing address, and billing postal code match.',
			'W' => 'Partial match: Street address does not match, but 9-digit postal code matches.',
			'X' => 'Match: Street address and 9-digit postal code match.',
			'Y' => 'Match: Street address and 5-digit postal code match.',
			'Z' => 'Partial match: Street address does not match, but 5-digit postal code matches.',
			'1' => 'Not supported: AVS is not supported for this processor or card type.',
			'2' => 'Unrecognized: The processor returned an unrecognized value for the AVS response.',
		);
		
		public $cvn_codes = array(
			'D' => 'The transaction was determined to be suspicious by the issuing bank.',
			'I' => 'The CVN failed the processor\'s data validation check.',
			'M' => 'The CVN matched.',
			'N' => 'The CVN did not match.',
			'P' => 'The CVN was not processed by the processor for an unspecified reason.',
			'S' => 'The CVN is on the card but waqs not included in the request.',
			'U' => 'Card verification is not supported by the issuing bank.',
			'X' => 'Card verification is not supported by the card association.',
			'1' => 'Card verification is not supported for this processor or card type.',
			'2' => 'An unrecognized result code was returned by the processor for the card verification response.',
			'3' => 'No result code was returned by the processor.',
		);
		
		public $result_codes = array(
			'100' => 'Successful transaction.',
			'101' => 'The request is missing one or more required fields.',
			'102' => 'One or more fields in the request contains invalid data.',
			'110' => 'Only a partial amount was approved.',
			'150' => 'Error: General system failure.',
			'151' => 'Error: The request was received but there was a server timeout.',
			'152' => 'Error: The request was received, but a service did not finish running in time.',
			'200' => 'The authorization request was approved by the issuing bank but declined by CyberSource because it did not pass the Address Verification Service (AVS) check.',
			'201' => 'The issuing bank has questions about the request.',
			'202' => 'Expired card.',
			'203' => 'General decline of the card.',
			'204' => 'Insufficient funds in the account.',
			'205' => 'Stolen or lost card.',
			'207' => 'Issuing bank unavailable.',
			'208' => 'Inactive card or card not authorized for card-not-present transactions.',
			'209' => 'American Express Card Identification Digits (CID) did not match.',
			'210' => 'The card has reached the credit limit.',
			'211' => 'Invalid CVN.',
			'221' => 'The customer matched an entry on the processor\'s negative file.',
			'230' => 'The authorization request was approved by the issuing bank but declined by CyberSource because it did not pass the CVN check.',
			'231' => 'Invalid credit card number.',
			'232' => 'The card type is not accepted by the payment processor.',
			'233' => 'General decline by the processor.',
			'234' => 'There is a problem with your CyberSource merchant configuration.',
			'235' => 'The requested amount exceeds the originally authorized amount.',
			'236' => 'Processor failure.',
			'237' => 'The authorization has already been reversed.',
			'238' => 'The authorization has already been captured.',
			'239' => 'The requested transaction amount must match the previous transaction amount.',
			'240' => 'The card type sent is invalid or does not correlate with the credit card number.',
			'241' => 'The request ID is invalid.',
			'242' => 'You requested a capture, but there is no corresponding, unused authorization record.',
			'243' => 'The transaction has already been settled or reversed.',
			'246' => 'The capture or credit is not voidable because the capture or credit information has laready been submitted to your processor. Or, you requested a void for a type of transaction that cannot be voided.',
			'247' => 'You requested a credit for a capture that was previously voided.',
			'250' => 'Error: The request was received, but there was a timeout at the payment processor.',
			'520' => 'The authorization request was approved by the issuing bank but declined by CyberSource based on your Smart Authorization settings.',
		);
		
		public $card_types = array(
			'Visa' => '001',
			'MasterCard' => '002',
			'American Express' => '003',
			'Discover' => '004',
			'Diners Club' => '005',
			'Carte Blanche' => '006',
			'JCB' => '007',
		);
		
		public $test_cards = array(
			'amex' => '378282246310005',
			'discover' => '6011111111111117',
			'mastercard' => '5555555555554444',
			'visa' => '4111111111111111',
		);
		
		public function __construct ( $merchant_id = null, $transaction_id = null, $environment = self::ENV_TEST ) {
			
			$this->merchant_id( $merchant_id );
			$this->transaction_id( $transaction_id );
			
			$this->environment( $environment );
			
		}
		
		public static function factory ( $merchant_id = null, $transaction_id = null, $environment = self::ENV_TEST ) {
			
			$class = __CLASS__;
			$object = new $class( $merchant_id, $transaction_id, $environment );
			
			return $object;
			
		}
		
		public function merchant_id ( $id ) {
			$this->merchant_id = $id;
			
			return $this;
		}
		
		public function transaction_id ( $id ) {
			$this->transaction_id = $id;
			
			return $this;
		}
		
		public function environment ( $env ) {
			$this->environment = $env;
			
			return $this;
		}
		
		public function reference_code ( $code ) {
			$this->reference_code = $code;
			
			return $this;
		}
		
		public function card ( $number, $expiration_month, $expiration_year, $cvn_code = null, $card_type = null ) {
			
			$this->card = array(
				'accountNumber' => $number,
				'expirationMonth' => $expiration_month,
				'expirationYear' => $expiration_year,
			);
			
			// if a cvn code was supplied, use it
			// note that cvIndicator is turned on automatically if we pass in a cvNumber
			if ( $cvn_code != null ) {
				$this->card['cvNumber'] = $cvn_code;
			}
			
			// and if we specified a card type, use that too
			if ( $card_type != null ) {
				// if the card type is numeric, we probably already specified the exact code, just use it
				if ( is_numeric( $card_type ) ) {
					$this->card['cardType'] = $card_type;
				}
				else {
					// otherwise, convert it from a textual name
					$this->card['cardType'] = $this->card_types[ $card_type ];
				}
			}
			
			return $this;
			
		}
		
		public function items ( $items = array() ) {
			
			foreach ( $items as $item )  {
				$this->add_item( $item['price'], $item['quantity'] );
			}
			
			return $this;
			
		}
		
		public function add_item ( $price, $quantity = 1 ) {
			
			$this->items[] = array(
				'unitPrice' => $price,
				'quantity' => $quantity,
			);
			
			return $this;
			
		}
		
		private function create_request ( ) {
			
			// build the class for the request
			$request = new stdClass();
			$request->merchantID = $this->merchant_id;
			$request->merchantReferenceCode = $this->reference_code;
			
			// some info CyberSource asks us to add for troubleshooting purposes
			$request->clientLibrary = 'CyberSourcePHP';
			$request->clientLibraryVersion = self::VERSION;
			$request->clientEnvironment = php_uname();
			
			// this also is pretty stupid, particularly the name
			$purchase_totals = new stdClass();
			$purchase_totals->currency = 'USD';
			$request->purchaseTotals = $purchase_totals;
			
			return $request;
			
		}
		
		private function create_items ( $request ) {
			
			// there is no container for items, which annoys me
			$request->item = array();
			$i = 0;
			foreach ( $this->items as $item ) {
				$it = new stdClass();
				$it->unitPrice = $item['unitPrice'];
				$it->quantity = $item['quantity'];
				$it->id = $i;
				
				$request->item[] = $it;
				
				$i++;
			}
			
			return $request;
			
		}
		
		private function create_bill_to ( ) {
			
			// build the billTo class
			$bill_to = new stdClass();
			
			// add all the bill_to fields
			foreach ( $this->bill_to as $k => $v ) {
				$bill_to->$k = $v;
			}
			
			return $bill_to;
			
		}
		
		private function create_card ( ) {
			
			// build the credit card class
			$card = new stdClass();
			
			foreach ( $this->card as $k => $v ) {
				$card->$k = $v;
			}
			
			return $card;
			
		}
		
		public function charge ( $amount = null ) {
			
			$request = $this->create_request();
			
			// we want to perform an authorization
			$cc_auth_service = new stdClass();
			$cc_auth_service->run = 'true';		// note that it's textual true so it doesn't get cast as an int
			$request->ccAuthService = $cc_auth_service;
			
			// and actually charge them
			$cc_capture_service = new stdClass();
			$cc_capture_service->run = 'true';
			$request->ccCaptureService = $cc_capture_service;
			
			// add billing info to the request
			$request->billTo = $this->create_bill_to();
			
			// add credit card info to the request
			$request->card = $this->create_card();
			
			// if there was an amount specified, just use it - otherwise add the individual items
			if ( $amount !== null ) {
				$request->purchaseTotals->grandTotalAmount = $amount;
			}
			else {
				$this->create_items( $request );
			}
			
			$response = $this->run_transaction( $request );
			
			return $response;
			
		}
		
		public function capture ( $request_token = null, $amount = null ) {
			
			$request = $this->create_request();
			
			$capture_service = new stdClass();
			$capture_service->run = 'true';
			$capture_service->authRequestToken = $request_token;
			$request->ccCaptureService = $capture_service;
			
			// if there was an amount specified, just use it - otherwise add the individual items
			if ( $amount !== null ) {
				$request->purchaseTotals->grandTotalAmount = $amount;
			}
			else {
				$this->create_items( $request );
			}
			
			$response = $this->run_transaction( $request );
			
			return $response;
			
		}
		
		public function credit ( $requestId ) {
			
			$request = $this->create_request();
			
			// we want to perform an authorization
			$cc_credit_service = new stdClass();
			$cc_credit_service->run = 'true';		// note that it's textual true so it doesn't get cast as an int
			$cc_credit_service->captureRequestID = $requestId;
			$request->ccCreditService = $cc_credit_service;
			
				// there is no container for items, which annoys me
			$request->item = array();
			$i = 0;
			foreach ( $this->items as $item ) {
				$it = new stdClass();
				$it->unitPrice = $item['unitPrice'];
				$it->quantity = $item['quantity'];
				$it->id = $i;
				
				$request->item[] = $it;
				
				$i++;
			}
			
			$response = $this->run_transaction( $request );
			
			return $response;
			
		}		
		
		/**
		 * Create a new payment subscription, either by performing a $0 authorization check on the credit card or using a 
		 * pre-created request token from an authorization request that's already been performed.
		 * 
		 * @param string $request_id The request ID received from an AuthReply statement, if applicable.
		 * @param boolean|null $auto_authorize Set to false to enable the disableAutoAuth flag to avoid an authorization and simply store the card. The default (null) means to omit the value, which means it'll use the setting on the account. Set to true to force an authorization, whether the account requires it or not.
		 * @return stdClass The raw response object from the SOAP endpoint
		 */
		public function create_subscription ( $request_id = null, $auto_authorize = null, $subscription_info = null ) {
			
			$request = $this->create_request();
			
			$subscription_create = new stdClass();
			$subscription_create->run = 'true';
			
			// if there is a request token passed in, reference it
			if ( $request_id != null ) {
				$subscription_create->paymentRequestID = $request_id;
			}
			else {
				
				if ( $auto_authorize === false ) {
					$subscription_create->disableAutoAuth = 'true';
				}
				else if ( $auto_authorize === true ) {
					$subscription_create->disableAutoAuth = 'false';
				}
				
			}
			
			$request->paySubscriptionCreateService = $subscription_create;
			
			if ( $subscription_info == null ) {
				// specify that this is an on-demand subscription, it should not auto-bill
				$subscription_info = new stdClass();
				$subscription_info->frequency = 'on-demand';
			}
			$request->recurringSubscriptionInfo = $subscription_info;
			
			// we only need to add billing info to the request if there is not a previous request token - otherwise it's contained in it
			if ( $request_id == null ) {

				// add billing info to the request
				$request->billTo = $this->create_bill_to();

				// add credit card info to the request
				$request->card = $this->create_card();
				
			}
			
			$response = $this->run_transaction( $request );
			
			// return just the subscription ID from the response
			return $response;
			
		}
		
		/**
		 * Delete the given Subscription ID permanently.
		 * 
		 * @param string $subscription_id The CyberSource Subscription ID to delete.
		 * @return stdClass The raw response object from the SOAP endpoint
		 */
		public function delete_subscription ( $subscription_id ) {
			
			$request = $this->create_request();
			
			$subscription_delete = new stdClass();
			$subscription_delete->run = 'true';
			$request->paySubscriptionDeleteService = $subscription_delete;
			
			$subscription_info = new stdClass();
			$subscription_info->subscriptionID = $subscription_id;
			$request->recurringSubscriptionInfo = $subscription_info;
			
			$response = $this->run_transaction( $request );
			
			return $response;
			
		}
		
		/**
		 * Charge the given Subscription ID a certain amount.
		 * 
		 * @param string $subscription_id The CyberSource Subscription ID to charge.
		 * @param float $amount The dollar amount to charge.
		 * @return stdClass The raw response object from the SOAP endpoint
		 */
		public function charge_subscription ( $subscription_id, $amount ) {
			
			$request = $this->create_request();
			
			// we want to perform an authorization
			$cc_auth_service = new stdClass();
			$cc_auth_service->run = 'true';		// note that it's textual true so it doesn't get cast as an int
			$request->ccAuthService = $cc_auth_service;
			
			// and actually charge them
			$cc_capture_service = new stdClass();
			$cc_capture_service->run = 'true';
			$request->ccCaptureService = $cc_capture_service;
			
			// actually remember to add the subscription ID that we're billing... duh!
			$subscription_info = new stdClass();
			$subscription_info->subscriptionID = $subscription_id;
			$request->recurringSubscriptionInfo = $subscription_info;
			
			$request->purchaseTotals->grandTotalAmount = $amount;
			
			$response = $this->run_transaction( $request );
			
			return $response;
			
		}
		
		public function update_subscription ( $subscription_id ) {
			
			$request = $this->create_request();
			
			// we want to update!
			$subscription_update = new stdClass();
			$subscription_update->run = 'true';
			$request->paySubscriptionUpdateService = $subscription_update;
			
			// add the subscription id that we're billing
			$subscription_info = new stdClass();
			$subscription_info->subscriptionID = $subscription_id;
			$request->recurringSubscriptionInfo = $subscription_info;
			
			// the only information that can change is the billing info, so load all that
			$request->billTo = $this->create_bill_to();
			
			$response = $this->run_transaction( $request );
			
			return $response;
			
		}
		
		public function retrieve_subscription ( $subscription_id ) {
			
			$request = $this->create_request();
			
			// we want to retrieve!
			$subscription_retrieve = new stdClass();
			$subscription_retrieve->run = 'true';
			$request->paySubscriptionRetrieveService = $subscription_retrieve;
			
			// the subscription ID we want to fetch data for
			$subscription_info = new stdClass();
			$subscription_info->subscriptionID = $subscription_id;
			$request->recurringSubscriptionInfo = $subscription_info;
			
			$response = $this->run_transaction( $request );
			
			return $response;
			
		}
		
		/**
		 * Performs a 0-dollar authorization on a credit card to test its validity.
		 * 
		 * @return stdClass The raw response object from the SOAP endpoint
		 */
		public function validate_card ( ) {
			
			$request = $this->create_request();
			
			$cc_auth_service = new stdClass();
			$cc_auth_service->run = 'true';
			$request->ccAuthService = $cc_auth_service;
			
			// add billing info to the request
			$request->billTo = $this->create_bill_to();
			
			// add credit card info to the request
			$request->card = $this->create_card();
			
			// set the grand total amount to 0, instead of including items
			$request->purchaseTotals->grandTotalAmount = 0;
			
			// run the authentication check
			$response = $this->run_transaction( $request );
			
			// if we didn't throw an exception everything went fine, just return the request token
			return $response;
			
		}
		
		public function authorize ( $amount = null ) {
			
			$request = $this->create_request();
			
			$cc_auth_service = new stdClass();
			$cc_auth_service->run = 'true';
			$request->ccAuthService = $cc_auth_service;
			
			// add billing info to the request
			$request->billTo = $this->create_bill_to();
			
			// add credit card info to the request
			$request->card = $this->create_card();
			
			// if there was an amount specified, just use it - otherwise add the individual items
			if ( $amount !== null ) {
				$request->purchaseTotals->grandTotalAmount = $amount;
			}
			else {
				$this->create_items( $request );
			}
			
			// run the authorization
			$response = $this->run_transaction( $request );
			
			// if we didn't throw an exception everything went fine, just return the response
			return $response;
			
		}
		
		protected function run_transaction ( $request ) {
			
			$context_options = array(
				'http' => array(
					'timeout' => $this->timeout,
				),
			);

			$context = stream_context_create( $context_options );

			// options we pass into the soap client
			$soap_options = array(
				'compression' => true,		// turn on HTTP compression
				'encoding' => 'utf-8',		// set the internal character encoding to avoid random conversions
				'exceptions' => true,		// throw SoapFault exceptions when there is an error
				'connection_timeout' => $this->timeout,
				'stream_context' => $context,
			);

			// if we're in test mode, don't cache the wsdl
			if ( $this->environment == self::ENV_TEST ) {
				$soap_options['cache_wsdl'] = WSDL_CACHE_NONE;
			}

			// if we're in production mode, cache the wsdl like crazy
			if ( $this->environment == self::ENV_PRODUCTION ) {
				$soap_options['cache_wsdl'] = WSDL_CACHE_BOTH;
			}

			try {
				// create the soap client
				$soap = new SoapClient( $this->environment, $soap_options );
			}
			catch ( SoapFault $sf ) {
				throw new CyberSource_Connection_Exception( $sf->getMessage(), $sf->getCode() );
			}
			
			// add the wsse token to the soap object, by reference
			$this->add_wsse_token( $soap );
			
			// save the request so you can get back what was generated at any point
			$this->request = $request;
			
			$response = $soap->runTransaction( $request );
			
			// save the whole response so you can get everything back even on an exception
			$this->response = $response;
			
			if ( $response->decision != 'ACCEPT' ) {
				
				// customize the error message if the reason indicates a field is missing
				if ( $response->reasonCode == 101 ) {
					
					if ( !isset( $response->missingField ) ) {
						$missing_fields = 'Unknown';
					}
					else if ( is_array( $response->missingField ) ) {
						$missing_fields = implode( ', ', $response->missingField );
					}
					else {
						$missing_fields = $response->missingField;
					}
					
					throw new CyberSource_Missing_Field_Exception( $missing_fields, 101 );
				}
				
				// customize the error message if the reason code indicates a field is invalid
				if ( $response->reasonCode == 102 ) {
					
					if ( !isset( $response->invalidField ) ) {
						$invalid_fields = 'Unknown';
					}
					else if ( is_array( $response->invalidField ) ) {
						$invalid_fields = implode( ', ', $response->invalidField );
					}
					else {
						$invalid_fields = $response->invalidField;
					}
					
					throw new CyberSource_Invalid_Field_Exception( $invalid_fields, 102 );
				}
				
				// otherwise, just throw a generic declined exception
				if ( $response->decision == 'ERROR' ) {
					// note that ERROR means some kind of system error or the processor rejected invalid data - it probably doesn't mean the card was actually declined
					throw new CyberSource_Error_Exception( $this->result_codes[ $response->reasonCode ], $response->reasonCode );
				}
				else {
					// declined, however, actually means declined. this would be decision 'REJECT', btw.
					throw new CyberSource_Declined_Exception( $this->result_codes[ $response->reasonCode ], $response->reasonCode );
				}
			}
			
			return $response;
			
		}
		
		/**
		 * Factory-pattern method for setting the billing information for this charge.
		 * 
		 * Required fields are:
		 *	firstName
		 *	lastName
		 *	street1
		 *	city
		 *	state
		 *	postalCode
		 *	country
		 *	email
		 * 
		 * @param array $info An associative array of the fields to set. Note the required fields above.
		 * @return \CyberSource The current object.
		 * @throws InvalidArgumentException Thrown when a required field is not present in the $info array.
		 */
		public function bill_to ( $info = array() ) {
			
			$fields = array(
				'firstName',
				'lastName',
				'street1',
				'city',
				'state',
				'postalCode',
				'country',
				'email',
			);
			
			foreach ( $fields as $field ) {
				if ( !isset( $info[ $field ] ) ) {
					throw new InvalidArgumentException( 'The bill to field ' . $field . ' is missing!' );
				}
			}
			
			// if no ip address was specified, assume it's the remote host
			if ( !isset( $info['ipAddress'] ) ) {
				$info['ipAddress'] = $this->get_ip();
			}
			
			$this->bill_to = $info;
			
			return $this;
			
		}
		
		/**
		 * Get the remote IP address, but try and take into account common proxy headers and the like.
		 * 
		 * @return string The client's IP address or 0.0.0.0 if we couldn't find it.
		 */
		private function get_ip ( ) {
			
			$headers = array(
				'HTTP_CLIENT_IP',
				'HTTP_FORWARDED',
				'HTTP_X_FORWARDED',
				'HTTP_X_FORWARDED_FOR',
				'REMOTE_ADDR',
			);
			
			foreach ( $headers as $header ) {
				if ( isset( $_SERVER[ $header ] ) ) {
					return $_SERVER[ $header ];
				}
			}
			
			// just in case none of them are set
			return '0.0.0.0';
			
		}
		
		private function add_wsse_token ( $soap ) {
			
			$wsse_namespace = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
			$type_namespace = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText';
			
			$user = new SoapVar( $this->merchant_id, XSD_STRING, null, $wsse_namespace, null, $wsse_namespace );
			$pass = new SoapVar( $this->transaction_id, XSD_STRING, null, $type_namespace, null, $wsse_namespace );
			
			// create the username token container object
			$username_token = new stdClass();
			$username_token->Username = $user;
			$username_token->Password = $pass;
			
			// convert the username token object into a soap var
			$username_token = new SoapVar( $username_token, SOAP_ENC_OBJECT, null, $wsse_namespace, 'UsernameToken', $wsse_namespace );
			
			// create the security container object
			$security = new stdClass();
			$security->UsernameToken = $username_token;
			
			// convert the security container object into a soap var
			$security = new SoapVar( $security, SOAP_ENC_OBJECT, null, $wsse_namespace, 'Security', $wsse_namespace );
			
			// create the header out of the security soap var
			$header = new SoapHeader( $wsse_namespace, 'Security', $security, true );
			
			// add the headers to the soap client
			$soap->__setSoapHeaders( $header );
			
		}
		
		/**
		 * Try to determine the type of card based on its number.
		 * 
		 * @see http://www.cybersource.com/support_center/management/best_practices/view.php?page_id=416
		 * @param int $card_number The credit card number
		 * @return string|null The name of the card type or null if it wasn't matched.
		 */
		public function card_type ( $card_number ) {
			
			$digits = str_split( $card_number );
			
			if ( strlen( $card_number ) == 15 && $digits[0] == 3 && ( $digits[1] == 4 || $digits[1] == 7 ) ) {
				return 'American Express';
			}
			else if ( strlen( $card_number ) == 14 && $digits[0] == 3 && in_array( $digits[1], array( 0, 6, 8 ) ) ) {
				return 'Diners Club';		// also Carte Blanche - how the hell am i supposed to know?
			}
			else if ( strlen( $card_number ) == 16 && (
				( substr( $card_number, 0, 8 ) >= 60110000 && substr( $card_number, 0, 8 ) <= 60119999 ) ||
				( substr( $card_number, 0, 8 ) >= 65000000 && substr( $card_number, 0, 8 ) <= 65999999 ) ||
				( substr( $card_number, 0, 8 ) >= 62212600 && substr( $card_number, 0, 8 ) <= 62292599 )
			) ) {
				return 'Discover';
			}
			else if ( strlen( $card_number ) == 15 && in_array( substr( $card_number, 0, 4 ), array( 2014, 2149 ) ) ) {
				return 'enRoute';
			}
			else if ( strlen( $card_number ) == 16 && (
				in_array( substr( $card_number, 0, 4 ), array( 3088, 3096, 3112, 3158, 3337 ) ) ||
				( substr( $card_number, 0, 8 ) >= 35280000 && substr( $card_number, 0, 8 ) <= 35899999 )
			) ) {
				return 'JCB';
			}
			else if ( strlen( $card_number ) == 16 && $digits[0] == 5 && $digits[1] >= 1 && $digits[1] <= 5 ) {
				return 'MasterCard';
			}
			else if ( ( strlen( $card_number ) == 13 || strlen( $card_number ) == 16 ) && $digits[0] == 4 ) {
				return 'Visa';
			}
			
			// otherwise, we don't know
			return null;
			
		}
		
	}
	
	class CyberSource_Exception extends Exception {}
	
	class CyberSource_Error_Exception extends CyberSource_Exception {}
	class CyberSource_Declined_Exception extends CyberSource_Exception {}
	class CyberSource_Connection_Exception extends CyberSource_Exception {}
	class CyberSource_Invalid_Field_Exception extends CyberSource_Exception {}
	class CyberSource_Missing_Field_Exception extends CyberSource_Exception {}

?>