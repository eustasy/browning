[Browning: A Mailgun Script](https://github.com/eustasy/browning-a-mailgun-script)
=======================

Browning is a tiny PHP function to send emails with Mailgun, that uses CURL instead of Mailgun's (slightly porky) library.

[![Normal](https://github.com/eustasy/browning/actions/workflows/normal.yml/badge.svg)](https://github.com/eustasy/browning/actions/workflows/normal.yml)
[![Code Climate](https://codeclimate.com/github/eustasy/browning/badges/gpa.svg)](https://codeclimate.com/github/eustasy/browning)

### Setup
If possible, make sure you have the following packages installed before using this script. It may work without some of them, but all are recommended. CURL is required.
`libmagic-dev php-dev libcurl3 php-curl`

### Usage
To use, do somethings like this.
```php
require '_settings/browning.default.php';
include '_settings/browning.custom.php';
require '_functions/browning/function.browning.php';

$Mail = Browning(
	'recepient@example.com',
	'Message Subject',
	'Text or HTML Body',
	'Sender Name',
	'reply-to@example.com',
	true // debug
);

if ( $Mail['Success'] ) {
	echo '<h2>Success! We managed to send the E-Mail.</h2>'.PHP_EOL;
	echo '<p class="sub-title">Thanks for believeing in us.</p>'.PHP_EOL;
} else {
	echo '<h2>Sorry, we failed to send the E-Mail.</h2>'.PHP_EOL;
	echo '<p class="sub-title error">'.$Mail['Error'].'</p>'.PHP_EOL;
	echo '<!--'.PHP_EOL;
	var_dump($Mail);
	echo PHP_EOL.'-->'.;
}
```
