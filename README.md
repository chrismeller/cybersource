Description
===========
A simple class for interfacing with some of the basic methods of the [CyberSource](http://cybersource.com) SOAP Toolkit API for processing credit card transactions online.

CyberSource encourages the use of their Simple Order API, which utilizes a custom PHP extension that hasn't been updated since 2007 (and doesn't support 64-bit servers). This class avoids the need to install a custom extension and provides a quick and easy entry point to their SOAP API instead.

License
-------

	Copyright 2011 Chris Meller

	Licensed under the Apache License, Version 2.0 (the "License");
	you may not use this file except in compliance with the License.
	You may obtain a copy of the License at

	    http://www.apache.org/licenses/LICENSE-2.0

	Unless required by applicable law or agreed to in writing, software
	distributed under the License is distributed on an "AS IS" BASIS,
	WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
	See the License for the specific language governing permissions and
	limitations under the License.

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
There are several invidivual examples available in the ``tests`` directory demonstrating how to call the commonly-used methods.

To run the examples first create a file under `tests/config.php` with the following keys:

```
<?php

$merchant_id = 'xxx';
$transaction_id = 'yyy';
$username = 'zzz';
$password = 'vvv';
```

Then run:

```
$ /path/to/composer.phar dump-autoload
```

This will create the autoload file for the classes of the library. You are now ready to execute an example:

```
php -d error_reporting=-1 -d display_errors tests/auth_amount.php
```