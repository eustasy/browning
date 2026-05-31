<?php

declare(strict_types=1);

/*
 * Browning configuration template.
 * https://github.com/eustasy/browning
 *
 * Copy this file out of vendor/ (e.g. to config/browning.php), fill in your
 * details, then load it and hand it to the classes:
 *
 *     $config  = require 'config/browning.php';
 *     $mailer  = Eustasy\Browning\Mailer::fromArray($config);
 *     $captcha = Eustasy\Browning\Recaptcha::fromArray($config);
 */

return [
    // Mailgun API URL — replace example.com with your verified Mailgun domain.
    // Sign up at https://mailgun.com/signup (first 10,000 mails a month free).
    'URL' => 'https://api.mailgun.net/v3/example.com',

    // Mailgun private API key — https://mailgun.com/cp (not the public key).
    'Key' => 'key-123456-abcdefg-789012-abc-34567',

    'Default' => [
        // Display name for the sender.
        'Regards' => 'Example Support',

        // Reply-to / from address. Please don't use noreply; should match the
        // domain in your API URL above.
        'ReplyTo' => 'support@example.com',
    ],

    // Optional: Google reCAPTCHA v2 keys — https://www.google.com/recaptcha/admin
    'Recaptcha' => [
        'SiteKey' => '0123456789abcdefghijklmnopqrstuvwxyz',
        'SecretKey' => '0123456789abcdefghijklmnopqrstuvwxyz',
    ],
];
