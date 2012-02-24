<?php

	error_reporting(-1);
	ini_set('display_errors', true);

	require('main.php');
	
	class Custom_CyberSource extends CyberSource {
		
		/**
		 * @var $test_amounts An array of reasonCode -> Amount that triggers it.
		 * 
		 * Note that the CyberSource documentation is frequently wrong about these, at least for GPN, so some trial and error was done.
		 */
		public $test_amounts = array(
			'200' => '5019.00',		// GPN Only
		);
		
		public static function factory ( $merchant_id = null, $transaction_id = null, $environment = self::ENV_TEST ) {
			
			$class = __CLASS__;
			$object = new $class( $merchant_id, $transaction_id, $environment );
			
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
				
				if ( isset( $this->request->ccAuthService ) && isset( $this->request->ccCaptureService ) && $this->response->reasonCode == 200 ) {
					$response = $this->capture( $this->response->requestToken, $this->response->ccAuthReply->amount );
				}
				else if ( isset( $this->request->ccAuthService ) && isset( $this->request->ccCaptureService ) && $this->response->reasonCode == 230 ) {
					$response = $this->capture( $this->response->requestToken, $this->response->ccAuthReply->amount );
				}
				else {
					print_r($this->request);print_r($this->response);
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
	$c = Custom_CyberSource::factory( $merchant_id, $transaction_id, CyberSource::ENV_TEST );
	
	$c->card( $c->test_cards['visa'], '12', '2013', '1111', 'Visa' )
		->bill_to( array(
			'firstName' => 'John',
			'lastName' => 'Tester',
			'street1' => '123 Main Street',
			'city' => 'Columbia',
			'state' => 'SC',
			'postalCode' => '29201',
			'country' => 'US',
			'email' => 'john.tester@example.com',
		) );
	
	// $5017.00 triggers error 200 - AVS rejected for GPN
	// $9005.00 with CVV of 1111 triggers error 230 - CVN rejected for GPN
	//$auth_response = $c->authorize( '9005.00' );
	
	//if ( !isset( $auth_response->requestToken ) ) {
	//	die('Authorization seems to have failed!');
	//}
	
	$auth_response = $c->authorize( '5017.00' );
	//print_r($auth_response);
	$sub_response = $c->create_subscription( $auth_response->requestID );
	$charge_response = $c->charge_subscription( $sub_response->paySubscriptionCreateReply->subscriptionID, 2 );
	
	print_r($charge_response);
	
	
	return;
	
	try {
		$c->charge( '5017.00' );
		echo 'Charged!';die();
	}
	catch ( CyberSource_Declined_Exception $e ) {
		echo 'Transaction declined';
	}
	
	echo '<pre>';
	print_r( $c->request );
	print_r( $c->response );
	echo '</pre>';

?>