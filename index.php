<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(-1);

	if ( is_readable(__DIR__.'/_settings/browning.custom.php') ) {
		require __DIR__.'/_settings/browning.default.php';
		require __DIR__.'/_settings/browning.custom.php';
	} else {
		include __DIR__.'/_templates/header.php';
		echo '<h3>Configuration not available.</h3>';
		echo '<p>You need to copy <code>_settings/browning.default.php</code> to <code>_settings/browning.custom.php</code> and edit it with keys for Mailgun and Recaptcha.</p>';
		include __DIR__.'/_templates/footer.php';
		exit;
	}

	if (
		isset($_POST['dear']) &&
		isset($_POST['subject']) &&
		isset($_POST['message'])
	) {

		if ( $Recaptcha['Enable'] ) {
			require __DIR__.'/_functions/browning/function.recaptcha.verify.php';
			$Recaptcha['Validity'] = Recaptcha_Verify($Recaptcha['SecretKey'], $_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
		}

		if ( $Recaptcha['Enable'] && !$Recaptcha['Validity']['Success'] ) {
			include __DIR__.'/_templates/header.php';
			echo '<h3>The reCAPTCHA wasn\'t entered correctly. Go back and try it again.</h3>';
			if ( !empty($Recaptcha['Validity']['Error']) ) {
				echo '<p>'.$Recaptcha['Validity']['Error'].'</p>';
			}
			include __DIR__.'/_templates/footer.php';
		} else if ( !$Recaptcha['Enable'] || $Recaptcha['Validity']['Success'] ) {
			// If Recaptcha is turned off OR it was entered correctly.
			require __DIR__.'/_functions/browning/function.browning.php';
			// Make sure you've loaded the script and the config before running this function
			$Mail = Browning($_POST['dear'], $_POST['subject'], $_POST['message'], $_POST['regards'], $_POST['regards']);
			/* Browning_Send(
				'to email',
				'subject',
				'message',
				'from name',
				'reply-to email',
				debug [boolean: true, false; default: false;]
			); */
			include __DIR__.'/_templates/header.php';
			if ( $Mail['Success'] ) {
				echo '<h2>Done!</h2>';
			} else {
				echo '<h2>Nooo!</h2>';
				var_dump($Mail);
			}
			// include __DIR__.'/_templates/done.php';
			include __DIR__.'/_templates/footer.php';
		}

	} else {
		include __DIR__.'/_templates/header.php';
		include __DIR__.'/_templates/form.php';
		include __DIR__.'/_templates/footer.php';
	}
