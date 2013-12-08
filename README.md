Browning: A Mailgun Script
=======================

Browning is a tiny PHP function to send emails with Mailgun, that uses CURL instead of Mailgun's (slightly porky) library.

###Setup
If possible, make sure you have the following packages installed before using this script. It may work without some of them, but all are recommended. CURL is required.
`libmagic-dev php5-dev libcurl3 php5-curl`

###Usage
To use, do somethings like this.
```
if(isset($_POST['dear'])&&isset($_POST['subject'])&&isset($_POST['message'])) { // If you're ready to fire

	require 'Browning_Send.php';

	$Mail =  Browning_Send($_POST['dear'], $_POST['subject'], $_POST['message'], $_POST['regards'], '');

	/* Browning_Send(
		'to email',
		'subject',
		'message',
		'from name',
		'reply-to email',
		recaptcha [boolean: true, false; default: false;],
		debug [boolean: true, false; default: false;]
	); */

	if($Mail===true) echo '<h2>Success!</h2>';

	else echo '<h2>Error: '.$Mail.'</h2>';

} else {
	// Show a form.
}
```
