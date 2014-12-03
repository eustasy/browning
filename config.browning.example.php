<?php

////	Browning: A Mailgun Script (v0.24)
// https://github.com/eustasy/browning-a-mailgun-script

////	Mailgun
// Sign up at https://mailgun.com/signup
// First 10,00 mails a month free.

// Mailgun API URL
// Replace example.com with your domain after signing up at
// $Browning['URL'] = 'https://api.mailgun.net/v2/example.com';
$Browning['URL'] = 'https://api.mailgun.net/v2/example.com';

// Mailgun API Key
// Use API Key found at https://mailgun.com/cp
// $Browning['Key'] = 'key-123456-abcdefg-789012-abc-34567';
$Browning['Key'] = 'key-123456-abcdefg-789012-abc-34567';
// Not the Public Key. We don't need that.

// From for Mail
// $Browning['Default']['Regards'] = 'Example Support';
$Browning['Default']['Regards'] = 'Example Support';

// Reply-to address
// Please don't use noreply.
// Should match the domain in your API URL.
// $Browning['Default']['ReplyTo'] = 'support@example.com';
$Browning['Default']['ReplyTo'] = 'support@example.com';

////	Recaptcha
// Sign up at https://www.google.com/recaptcha/admin

// Enable

// You shouldn't need to change this unless you move the file.
$Recaptcha['Location'] = __DIR__.'/libs/recaptchalib.php';

// This is the public key you got from Google.
// They call this a "Site key".
$Recaptcha['Public'] = '0123456789abcdefghijklmnopqrstvwxyzABCDE';

// This is the secret one. Don't get them mixed up!
$Recaptcha['Secret'] = 'FGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghi';

// Disable

// $Recaptcha = false;