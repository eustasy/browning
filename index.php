<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(-1);

	if ( is_readable(__DIR__.'/_settings/browning.custom.php') ) {
		require __DIR__.'/_settings/browning.default.php';
		require __DIR__.'/_settings/browning.custom.php';
	} else {
		include __DIR__.'/_templates/header.php';
		echo '<div class="smablet-quarter"></div>';
		echo '<div class="whole smablet-half">';
		echo '<h3>Configuration not available.</h3>';
		echo '<p>You need to copy <code class="background-flatui-midnight-blue color-white rounded display-inline-block">_settings/browning.default.php</code> to <code  class="background-midnight-blue color-white rounded display-inline-block">_settings/browning.custom.php</code> and edit it with keys for Mailgun and Recaptcha.</p>';
		echo '</div>';
		echo '<div class="smablet-quarter"></div>';
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
			include __DIR__.'/_templates/header.php';
			if ( $Mail['Success'] ) {
				echo '<h2>Success! We managed to send the E-Mail.</h2>'.PHP_EOL;
				echo '<p class="sub-title">A message has been sent somewhere. It\'s that easy!</p>'.PHP_EOL;
			} else {
				echo '<h2>Sorry, we failed to send the E-Mail.</h2>'.PHP_EOL;
				echo '<p class="sub-title error">'.$Mail['Error'].'</p>'.PHP_EOL;
				echo '<code class="background-flatui-midnight-blue color-white rounded whole">';
				var_dump($Mail);
				echo '</code>';
			}
			include __DIR__.'/_templates/footer.php';
		}

	} else {
		include __DIR__.'/_templates/header.php';
		include __DIR__.'/_templates/form.php';
		include __DIR__.'/_templates/code.php';
		include __DIR__.'/_templates/footer.php';
	}
