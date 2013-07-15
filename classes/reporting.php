<?php
	
	namespace CyberSource;

	class Reporting {

		const ENV_TEST = 'ebctest.cybersource.com/ebctest';
		const ENV_PRODUCTION = 'ebc.cybersource.com/ebc';

		const VERSION = '0.1';
		const API_VERSION = '2011-03';		// there is no version; we read the March, 2011 Reporting Developer's Guide

		public $environment = self::ENV_TEST;

		public $merchant_id;
		public $username;
		public $password;

		public function __construct ( $merchant_id = null, $username = null, $password = null, $environment = self::ENV_TEST ) {

			$this->merchant_id( $merchant_id );
			$this->username( $username );
			$this->password( $password );

			$this->environment( $environment );

		}

		public static function factory ( $merchant_id = null, $username = null, $password = null, $environment = self::ENV_TEST ) {

			$class = __CLASS__;
			$object = new $class( $merchant_id, $username, $password, $environment );

			return $object;

		}

		public function merchant_id ( $id ) {
			$this->merchant_id = $id;

			return $this;
		}

		public function username ( $id ) {
			$this->username = $id;

			return $this;
		}

		public function password ( $id ) {
			$this->password = $id;

			return $this;
		}

		public function environment ( $env ) {
			$this->environment = $env;

			return $this;
		}

		/**
		 * Gets the Payment Submission Detail Report from CyberSource for the given day.
		 *
		 * @param  string $date Any string that's compatible with DateTime.
		 * @return array       An array of the records returned, as parsed from the CSV.
		 * @throws CyberSource_Report_Not_Found_Exception
		 * @throws CyberSource_Report_Exception
		 */
		public function payment_submission_detail ( $date = 'yesterday' ) {

			if ( !$date instanceof DateTime ) {
				$date = new DateTime($date);
			}

			// get the right host and substitute in our username and password for http basic authentication
			$url = 'https://' . $this->username . ':' . $this->password . '@' . $this->environment . '/DownloadReport/' . $date->format('Y') . '/' . $date->format('m') . '/' . $date->format('d') . '/' . $this->merchant_id . '/PaymentSubmissionDetailReport.csv';

			$result = @file_get_contents( $url );

			if ( $result === false ) {

				// this would be a lot easier if we could just have an error handler that throws exceptions, but here it is...
				$error = error_get_last();

				if ( isset( $error['message'] ) ) {

					// try to parse out the specific message, minus the function and crap
					$message = $error['message'];

					preg_match( '/failed to open stream: (.*)/', $message, $matches );

					if ( isset( $matches[1] ) ) {
						$message = $matches[1];
					}

					if ( strpos( $message, 'The report requested cannot be found on this server' ) !== false ) {
						throw new CyberSource_Report_Not_Found_Exception( $message, 400 );		// code 400? it's an HTTP 400 error. get it?
					}
					else {
						// we don't know exactly what type of error, throw a generic error
						throw new CyberSource_Report_Exception( $message );
					}

				}

				// something happened, but we dont' know what - die!
				throw new CyberSource_Report_Exception();

			}

			// parse out the results
			// but first, remove the first line - it's a header
			$result = substr( $result, strpos( $result, "\n" ) + strlen( "\n" ) );

			$records = $this->str_getcsv( $result );

			return $records;

		}

		/**
		 * A more basic version of the native str_getcsv that's only available in PHP 5.3+ - it wraps around the age-old fgetcsv, which does what we need.
		 *
		 * Oh yeah, and it keys the arrays. That's nifty, no?
		 *
		 * @link http://php.net/fgetcsv
		 */
		private function str_getcsv ( $input, $delimiter = ',', $enclosure = '"', $escape = '\\' ) {

			// open a temporary "file" that's actually just in memory
			$t = fopen( 'php://memory', 'rw' );

			// write the contents of our CSV to it
			fwrite( $t, $input );

			// skip back to the beginning of the file
			fseek( $t, 0 );

			// get the first row, they're the headers
			$headers = fgetcsv( $t, null, $delimiter, $enclosure, $escape );

			$rows = array();
			while( !feof( $t ) ) {
				$row = fgetcsv( $t, null, $delimiter, $enclosure, $escape );

				$row = array_combine( $headers, $row );

				$rows[] = $row;
			}

			fclose( $t );

			return $rows;

		}

	}

	class CyberSource_Report_Exception extends CyberSource_Exception {}
	class CyberSource_Report_Not_Found_Exception extends CyberSource_Report_Exception {}

?>