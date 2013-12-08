<?php



function Browning_Send($dear, $subject, $message, $regards, $replyto, $Recaptcha=false, $Debug=false){



	if(!isset($dear)) return 'No email address defined for recipient.';
	if(!isset($subject)) return 'No subject for message.';
	if(!isset($message)) return 'You must enter a message, to send a message.';



	require 'Browning_Config.php';



	if($Recaptcha) {
		require('recaptcha/recaptchalib.php');

		$Recaptcha_Response = recaptcha_check_answer(
			$Recaptcha_Secret,
			$_SERVER['REMOTE_ADDR'],
			$_POST['recaptcha_challenge_field'],
			$_POST['recaptcha_response_field']
		);

		if (!$Recaptcha_Response->is_valid) return 'The reCAPTCHA wasn\'t entered correctly. Go back and try it again. (reCAPTCHA said: '.$Recaptcha_Response->error.')';
	}



	$Browning_Dear = $dear;
	$Browning_Subject = $subject;
	$Browning_Message = $message;

	if(isset($regards) && !empty($regards)) {
		$Browning_Regards = $regards;
	} else {
		$Browning_Regards = $Browning_Global_Regards;
	}

	if(isset($replyto) && !empty($replyto)) {
		$Browning_ReplyTo = $replyto;
	} else {
		$Browning_ReplyTo = $Browning_Global_ReplyTo;
	}



	$Browning_Curl = curl_init();

	curl_setopt($Browning_Curl, CURLOPT_URL, $Browning_URL);
	curl_setopt($Browning_Curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($Browning_Curl, CURLOPT_USERPWD, 'api:'.$Browning_Key);
	curl_setopt($Browning_Curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt_array($Browning_Curl, array(
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => array(
			'from' => $Browning_Regards.' <'.$Browning_ReplyTo.'>',
			'to' => $Browning_Dear,
			'subject' => $Browning_Subject,
			'text' => $Browning_Message
		)
	));

	$Browning_Response = curl_exec($Browning_Curl);
	$Browning_Info = curl_getinfo($Browning_Curl);

	if(curl_errno($Browning_Curl)) return curl_errno($Browning_Curl).' Error: '.curl_error($Browning_Curl);
	if(!$Browning_Response) return 'Unable to send email. Check your configuration and keys.';

	if($Debug) {
		var_dump($Browning_Response);
		var_dump($Browning_Info);
	}

	curl_close($Browning_Curl);

	return true;
}
