<?php

////	Recaptcha Verify
// Check if Recaptcha response is valid.
// https://developers.google.com/recaptcha/docs/verify
// curl POST https://www.google.com/recaptcha/api/siteverify

function Recaptcha_Verify($RecaptchaSecret, $Response, $UserIP = false, $Debug = false)
{

	$Check = curl_init();

	curl_setopt($Check, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
	curl_setopt($Check, CURLOPT_RETURNTRANSFER, true);
	curl_setopt_array($Check, array(
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => array(
			'secret' => $RecaptchaSecret,
			'response' => $Response,
			'remoteip' => $UserIP
		)
	));

	$Response = curl_exec($Check);
	$CheckError = curl_errno($Check);
	$CheckErrorMessage = curl_error($Check);
	$Response = json_decode($Response, true);
	$Info = curl_getinfo($Check);

	if ($Debug) {
		echo '$Info is ';
		var_dump($Info);
		echo PHP_EOL;
		echo '$Response is ';
		var_dump($Response);
		echo PHP_EOL;
	}

	if ($CheckError) {
		return array('Success' => false, 'Error' => $CheckError . ' Error: ' . $CheckErrorMessage);
	} else {
		$Response['Success'] = $Response['success'];
		return $Response;
	}
}
