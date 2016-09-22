<?php

////	Recaptcha Verify
// Check if Recaptcha response is valid.
// https://developers.google.com/recaptcha/docs/verify
// curl POST https://www.google.com/recaptcha/api/siteverify

function Recaptcha_Verify($RecaptchaSecret, $Response, $UserIP = false, $Debug = false) {

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
	$Response = json_decode($Response, true);
	$Info = curl_getinfo($Check);

	if ( $Debug ) {
		echo '$Info is ';
		var_dump($Info);
		echo PHP_EOL;
		echo '$Response is ';
		var_dump($Response);
		echo PHP_EOL;
	}

	if ( curl_errno($Check) ) {
		return array('Success' => false, 'Error' => curl_errno($Check).' Error: '.curl_error($Check));

	} else {
		curl_close($Check);
		$Response['Success'] = $Response['success'];
		return $Response;
	}

}
