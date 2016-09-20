<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(-1);

	if ( is_readable('config.browning.php') ) {
		require 'config.browning.php';
	} else {
		include __DIR__.'/assets/header.php';
		echo '<h3>Configuration not available.</h3>';
		echo '<p>You need to copy <code>config.browning.example.php</code> to <code>config.browning.php</code> and edit it with keys for Mailgun and Recaptcha.</p>';
		include __DIR__.'/assets/footer.php';
		exit();
	}

	if (
		isset($_POST['dear']) &&
		isset($_POST['subject']) &&
		isset($_POST['message'])
	) {

		if ( $Recaptcha['Enable'] ) {
			$Recaptcha['SecretKey'];
			$_SERVER['REMOTE_ADDR'];
			$_POST['g-recaptcha-response'];
			// TODO Check if Recaptcha response is valid.
			// https://developers.google.com/recaptcha/docs/verify
			// curl POST https://www.google.com/recaptcha/api/siteverify
			if ( !$Recaptcha['Valid'] ) {
				include __DIR__.'/assets/header.php';
				echo '<h3>The reCAPTCHA wasn\'t entered correctly. Go back and try it again.</h3>';
				echo '<p>'.$Recaptcha['Error'].'</p>';
				include __DIR__.'/assets/footer.php';
				exit();
			}
		}

		// If Recaptcha is turned off OR it was entered correctly.
		if ( !isset($Recaptcha) || !$Recaptcha || $Recaptcha['Response']->is_valid ) {
			require 'function.browning.php';
			// Make sure you've loaded the script and the config before running this function
			$Mail = Browning($_POST['dear'], $_POST['subject'], $_POST['message'], $_POST['regards'], '');
			/* Browning_Send(
				'to email',
				'subject',
				'message',
				'from name',
				'reply-to email',
				debug [boolean: true, false; default: false;]
			); */
			include __DIR__.'/assets/header.php';
			if ( $Mail['Success'] ) {
				echo '<h2>Done!</h2>';
			} else {
				echo '<h2>Nooo!</h2>';
				var_dump($Mail);
			}
			// include __DIR__.'/assets/done.php';
			include __DIR__.'/assets/footer.php';
		}

	} else {
		include __DIR__.'/assets/header.php';
		include __DIR__.'/assets/form.php';
		include __DIR__.'/assets/footer.php';
	}
