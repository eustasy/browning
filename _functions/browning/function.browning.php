<?php

/*

Browning: A Mailgun Script (v0.28)
https://github.com/eustasy/browning-a-mailgun-script
====================================================

Browning is a tiny PHP function to send emails with Mailgun,
that uses CURL instead of Mailgun's (slightly porky) library.

Copyright (c) 2016 eustasy under the MIT License

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

function Browning($Dear, $Subject, $Message, $Regards = false, $ReplyTo = false, $Debug = false) {

	global $Browning;

	if ( empty($Dear) ) {
		return array('Error' => 'No email address defined for recipient.', 'Success' => false);

	} else if ( empty($Subject) ) {
		return array('Error' => 'No subject for message.', 'Success' => false);

	} else if ( empty($Message) ) {
		return array('Error' => 'You must enter a message, to send a message.', 'Success' => false);

	} else {

		$Browning['Dear'] = $Dear;
		$Browning['Subject'] = $Subject;
		$Browning['Message'] = $Message;

		if ( $Regards ) {
			$Browning['Regards'] = $Regards;
		} else {
			$Browning['Regards'] = $Browning['Default']['Regards'];
		}

		if ( $ReplyTo ) {
			$Browning['ReplyTo'] = $ReplyTo;
		} else {
			$Browning['ReplyTo'] = $Browning['Default']['ReplyTo'];
		}

		$Browning['Curl'] = curl_init();

		curl_setopt($Browning['Curl'], CURLOPT_URL, $Browning['URL'].'/messages');
		curl_setopt($Browning['Curl'], CURLOPT_RETURNTRANSFER, true);
		curl_setopt($Browning['Curl'], CURLOPT_USERPWD, 'api:'.$Browning['Key']);
		curl_setopt($Browning['Curl'], CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt_array($Browning['Curl'], array(
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => array(
				'from' => $Browning['Regards'].' <'.$Browning['ReplyTo'].'>',
				'to' => $Browning['Dear'],
				'subject' => $Browning['Subject'],
				'text' => $Browning['Message']
			)
		));

		$Browning['Response'] = curl_exec($Browning['Curl']);
		$Browning['Info'] = curl_getinfo($Browning['Curl']);

		if ( $Debug ) {
			echo 'Info is ';
			var_dump($Browning['Info']);
			echo PHP_EOL;
			echo 'Response is ';
			var_dump($Browning['Response']);
			echo PHP_EOL;
		}

		if ( curl_errno($Browning['Curl']) ) {
			return curl_errno($Browning['Curl']).' Error: '.curl_error($Browning['Curl']);

		} else if ( !$Browning['Response'] ) {
			return array('Error' => 'Unable to send email at this time. Please try again later.', 'Success' => false);

		} else if ( $Browning['Response'] == 'Forbidden' ) {
			return array('Error' => 'This website is unable to send email. <!-- Check your configuration and keys. -->', 'Success' => false);

		} else {
			curl_close($Browning['Curl']);
			return array('Error' => false, 'Success' => true);
		}

	}

}
