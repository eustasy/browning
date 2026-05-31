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
    // Mailgun API URL
    // Replace `<`example.com`>` with your verified Mailgun domain.
    'URL' => 'https://api.mailgun.net/v3/example.com',

    // Mailgun private API key.
    // Get it from "Domain Settings" > "Sending Keys" in your Mailgun dashboard.
    'Key' => '<your-mailgun-api-key>',

    'Default' => [
        // Display name for the sender.
        'Regards' => 'Example Support',

        // Reply-to / from address. Please don't use noreply; should match the
        // domain in your API URL above.
        'ReplyTo' => 'support@example.com',
    ],

    // Optional: Google reCAPTCHA v2 keys — https://www.google.com/recaptcha/admin
    'Recaptcha' => [
        'SiteKey' => '<your-recaptcha-site-key>',
        'SecretKey' => '<your-recaptcha-secret-key>',
    ],
];
