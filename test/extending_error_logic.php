<?php

	error_reporting(0);
	ini_set('display_errors', false);

	//require realpath(dirname( __FILE__ ) . '/../vendor/autoload.php');
	require realpath(dirname( __FILE__ ) . '/../classes/CyberSource/CyberSource.php');
	require realpath(dirname( __FILE__ ) . '/config.php');

	class Custom_CyberSource extends \CyberSource\CyberSource {
		
		/**
		 * @var $test_amounts An array of reasonCode -> Amount that triggers it.
		 * 
		 * Note that the CyberSource documentation is frequently wrong about these, at least for GPN, so some trial and error was done.
		 */
		public $test_amounts = array(
			'200' => '5019.00',		// GPN Only
		);
		
		public static function factory ( $merchant_id = null, $transaction_key = null, $environment = self::ENV_TEST ) {
			
			$class = __CLASS__;
			$object = new $class( $merchant_id, $transaction_key, $environment );
			
			return $object;
			
		}
		
		protected function run_transaction ( $request ) {
			
			// for any request that runs an authorization and a capture, ignore the AVS or CV response we get from the processor - codes 200 and 230
			//if ( isset( $request->ccAuthService ) && isset( $request->ccCaptureService ) ) {
				$business_rules = new stdClass();
				$business_rules->ignoreAVSResult = 'true';
				$business_rules->ignoreCVResult = 'true';
				
				$request->businessRules = $business_rules;
			//}
			
			try {
				$response = parent::run_transaction( $request );
			}
			catch ( CyberSource_Declined_Exception $e_declined ) {
				
				if ( isset( $this->request->ccAuthService ) && 
					 isset( $this->request->ccCaptureService ) &&
					 $this->response->reasonCode == 200 ) {

					$response = $this->capture( $this->response->requestToken, $this->response->ccAuthReply->amount );
				}
				else if ( isset( $this->request->ccAuthService ) &&
					      isset( $this->request->ccCaptureService ) &&
					      $this->response->reasonCode == 230 ) {

					$response = $this->capture( $this->response->requestToken, $this->response->ccAuthReply->amount );
				}
				else {
					print_r($this->request);
					print_r($this->response);
					throw $e_declined;
				}
				
			}
			catch ( CyberSource_Missing_Field_Exception $e_missing ) {
				
				$message = $this->result_codes[ $this->response->reasonCode ];
					
				if ( isset( $this->response->missingField ) ) {
					$message .= ' (' . $e_missing->getMessage() . ')';
				}
				
				throw new CyberSource_Missing_Field_Exception( $message, $e_missing->getCode() );
				
			}
			catch ( CyberSource_Invalid_Field_Exception $e_invalid ) {
				
				$message = $this->result_codes[ $this->response->reasonCode ];
				
				if ( isset( $this->response->invalidField ) ) {
					$message .= ' (' . $e_invalid->getMessage() . ')';
				}
				
				throw new CyberSource_Invalid_Field_Exception( $message, $e_invalid->getCode() );
				
			}
			
			return $response;
			
		}
		
	}
	
	// note we use our custom class, rather than using the main CyberSource class
	$c = Custom_CyberSource::factory( $merchant_id, $transaction_key, CyberSource\CyberSource::ENV_TEST );
	
	$c->card( $c->test_cards['visa'], '12', '2022', '1111', 'Visa' )
		->bill_to( array(
			'firstName' => 'John',
			'lastName' => 'Doe',
			'street1' => '123 Main Street',
			'city' => 'Columbia',
			'state' => 'SC',
			'postalCode' => '29201',
			'country' => 'US',
			'email' => 'john.doe@example.com',
		) );
	

	header("Content-Type: text/plain");
	
	$c->reference_code( time() );

	// $5017.00 triggers error 200 - AVS rejected for GPN
	// $9005.00 with CVV of 1111 triggers error 230 - CVN rejected for GPN

	try {

		//$auth_response = $c->authorize('5017.00');   // 203 - General decline of the card.
		$auth_response = $c->authorize('9005.00'); // => ACCEPT

		if ( !isset( $auth_response->requestToken ) ) {
			die('Authorization seems to have failed!');
		}

		print_r($auth_response);
	}
	// catch ( CyberSource_Declined_Exception $e ) {
	// 	echo 'Transaction declined';
	// }
	catch (Exception $e ) {
		print_r($e);
		die();
	}

	$sub_response = $c->create_subscription( $auth_response->requestID );
	print_r($sub_response);

	$charge_response = $c->charge_subscription( $sub_response->paySubscriptionCreateReply->subscriptionID, 2 );
	print_r($charge_response);
	
	try {
		$c->charge( '5017.00' );
		echo 'Charged!';die();
	}
	catch ( CyberSource_Declined_Exception $e ) {
		echo 'Transaction declined';
	}
	
	
	print_r( $c->request );
	print_r( $c->response );
	

// EOL