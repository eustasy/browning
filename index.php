<?php

	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(-1);

	if(isset($_POST['dear'])&&isset($_POST['subject'])&&isset($_POST['message'])) { // If you're ready to fire

		require 'Recaptcha_Config.php';
		if($Recaptcha) {
			require 'recaptchalib.php';
			$Recaptcha_Response = recaptcha_check_answer ($Recaptcha_Secret, $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);
			if(!$Recaptcha_Response->is_valid) {
				require 'header.php';
				echo '<h3>The reCAPTCHA wasn\'t entered correctly. Go back and try it again.</h3>';
				echo '<p>'.$Recaptcha_Response->error.'</p>';
				require 'footer.php';
				exit();
			}
		}

		// If Recaptcha is turned off OR it was entered correctly.
		if($Recaptcha == false || $Recaptcha_Response->is_valid) {
			require 'Browning_Send.php';
			// Make sure you've loaded the script and the config before running this function
			$Mail =	Browning_Send($_POST['dear'], $_POST['subject'], $_POST['message'], $_POST['regards'], '');
			/* Browning_Send(
				'to email',
				'subject',
				'message',
				'from name',
				'reply-to email',
				debug [boolean: true, false; default: false;]
			); */
			require 'header.php';
			if($Mail===true) echo '<h2>Done!</h2>';
			else echo '<h2>'.$Mail.'</h2>';
			// require 'done.php';
			require 'footer.php';
		}

	} else {
		require 'header.php';
		require 'style.php';
		require 'form.php';
		require 'footer.php';
	}

 ?>
