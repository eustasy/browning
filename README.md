[Browning: A Mailgun Script](https://github.com/eustasy/browning-a-mailgun-script)
=======================

Browning is a tiny PHP function to send emails with Mailgun, that uses CURL instead of Mailgun's (slightly porky) library.

### Setup
If possible, make sure you have the following packages installed before using this script. It may work without some of them, but all are recommended. CURL is required.
`libmagic-dev php-dev libcurl3 php-curl`

### Usage
To use, do somethings like this.
```
// If you're ready to fire
if ( isset($_POST['dear']) && isset($_POST['subject']) && isset($_POST['message']) ) {

	require '_settings/browning.default.php';
	include '_settings/browning.custom.php';
	require '_functions/browning/function.browning.php';

	$Mail =  Browning($_POST['dear'], $_POST['subject'], $_POST['message'], $_POST['regards'], '');

	/* Browning(
		'to email',
		'subject',
		'message',
		'from name',
		'reply-to email',
		debug [boolean: true, false; default: false;]
	); */

	if ( $Mail['Success'] ) {
		echo '<h2>Success!</h2>';
	} else {
		echo '<h2>Failure!</h2>';
		var_dump($Mail);
	}

} else {
	// Show a form.
}
```
