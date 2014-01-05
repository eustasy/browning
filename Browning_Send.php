<?php

/*

Browning: A Mailgun Script
https://github.com/eustasy/browning-a-mailgun-script
==========================

Browning is a tiny PHP function to send emails with Mailgun, that uses CURL instead of Mailgun's (slightly porky) library.

Copyright (c) 2013 eustasy under the MIT License

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.


*/

function Browning_Send($Dear, $Subject, $Message, $Regards=false, $ReplyTo=false, $Recaptcha=false, $Debug=false){

	if(!isset($Dear)) return 'No email address defined for recipient.';
	if(!isset($Subject)) return 'No subject for message.';
	if(!isset($Message)) return 'You must enter a message, to send a message.';

	require 'Browning_Config.php';

	$Browning_Dear = $Dear;
	$Browning_Subject = $Subject;
	$Browning_Message = $Message;
	if($Regards && !empty($Regards)) {
		$Browning_Regards = $Regards;
	} else {
		$Browning_Regards = $Browning_Global_Regards;
	}
	if($ReplyTo && !empty($ReplyTo)) {
		$Browning_ReplyTo = $ReplyTo;
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

	if($Debug) {
		var_dump($Browning_Response);
		var_dump($Browning_Info);
	}

	if(curl_errno($Browning_Curl)) return curl_errno($Browning_Curl).' Error: '.curl_error($Browning_Curl);
	if(!$Browning_Response) return 'Unable to send email. Check your configuration and keys.';

	curl_close($Browning_Curl);

	return true;
}
