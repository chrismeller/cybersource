Description
===========
A simple class for interfacing with some of the most basic methods of the [CyberSource](http://cybersource.com) SOAP Toolkit API for processing credit card transactions online.

CyberSource encourages the use of their Simple Order API, which utilizes a custom PHP extension that hasn't been updated since 2007. This class avoids the need to install a custom extension and provides a quick and easy entry point to their SOAP API instead.

API Documentation
-----------------
The [documentation](http://www.cybersource.com/developers/develop/integration_methods/simple_order_and_soap_toolkit_api/) provided by CyberSource is quite lacking. If you need to expand the methods made available your best bet is to look at the Simple Order API documentation and try to emulate it as closely as possible via the SOAP interface. With some trial and error you'll see that it's quite similar.

Usage
=====
Include the class in your code and instantiate the ``CyberSource`` class with your Merchant ID and Transaction ID:

	$c = new CyberSource( $merchant_id, $transaction_id );

A factory pattern is also provided, for easy method chaining:

	$result = CyberSource::factory( $merchant_id, $transaction_id )
		->card( '4111111111111111', '12', '2013', '123' )
		->add_item( 5 )
		->charge();

Examples
--------
There are several "unit tests" available in the ``tests`` directory, with full examples for calling the commonly-used methods.