<?php

	// Browning: A Mailgun Script
	// https://github.com/eustasy/browning-a-mailgun-script

	// Sign up at https://mailgun.com/signup
	// First 10,00 mails a month free.
	// This should be more than adequate for Password Resets.
	// If you are not using accounts, or intend to replace the mailing system, this is not required.

	// Mailgun API URL
	// Replace example.com with your domain after signing up at
	// $Browning_URL = 'https://api.mailgun.net/v2/example.com/messages';
	$Browning_URL = 'https://api.mailgun.net/v2/example.com/messages';

	// Mailgun API Key
	// Use API Key found at https://mailgun.com/cp
	// $Mail_Key = 'key-123456-abcdefg-789012-abc-34567';
	$Browning_Key = 'key-123456-abcdefg-789012-abc-34567';
	// Not Public Key

	// From for Mail
	// $Browning_Gloabl_Regards = 'Example Support';
	$Browning_Global_Regards = 'Example Support';

	// Reply-to address
	// Please don't use noreply.
	// Should match the domain in your API URL.
	// $Browning_Global_ReplyTo = 'support@example.com';
	$Browning_Global_ReplyTo = 'support@example.com';

	// Get your Public and Private Recaptcha Keys (optional)
	// https://www.google.com/recaptcha/admin/list
	$Recaptcha_Public = '01234567890abcdefghijklmnopqrstuvwxyz012';
	$Recaptcha_Secret = '01234567890abcdefghijklmnopqrstuvwxyz012';
