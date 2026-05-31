# Browning: A Mailgun Script (v0.30)

**Browning is a tiny PHP library to send emails with Mailgun, that uses CURL instead of Mailgun's (slightly porky) library.**

[![Normal (PHP)](https://github.com/eustasy/browning/actions/workflows/php.yml/badge.svg)](https://github.com/eustasy/browning/actions/workflows/php.yml)
[![Normal (Markdown)](https://github.com/eustasy/browning/actions/workflows/md.yml/badge.svg)](https://github.com/eustasy/browning/actions/workflows/md.yml)
[![Normal (Security)](https://github.com/eustasy/browning/actions/workflows/security.yml/badge.svg)](https://github.com/eustasy/browning/actions/workflows/security.yml)
[![Test (PHP)](https://github.com/eustasy/browning/actions/workflows/test-php.yml/badge.svg)](https://github.com/eustasy/browning/actions/workflows/test-php.yml)
[![CodeQL](https://github.com/eustasy/browning/actions/workflows/github-code-scanning/codeql/badge.svg)](https://github.com/eustasy/browning/actions/workflows/github-code-scanning/codeql)
[![Maintainability](https://qlty.sh/gh/eustasy/projects/browning/maintainability.svg)](https://qlty.sh/gh/eustasy/projects/browning)
[![Code Coverage](https://qlty.sh/gh/eustasy/projects/browning/coverage.svg)](https://qlty.sh/gh/eustasy/projects/browning)

Requires PHP 8.1+ and the cURL extension.

## Installation

```sh
composer require eustasy/browning
```

Then include the Composer autoloader:

```php
require 'vendor/autoload.php';
```

This makes the `Eustasy\Browning\Mailer` and `Eustasy\Browning\Recaptcha` classes available.

## 1. Setup with Mailgun

The cURL extension is required. On Debian/Ubuntu:

```sh
sudo apt-get install php-curl
```

[Sign up for Mailgun](https://signup.mailgun.com/new/signup) — the free tier covers 100 emails per day.
See [pricing](https://www.mailgun.com/pricing/) for the paid tiers.

Copy the bundled config template out of `vendor/`, for example to `config/browning.php`:

```sh
cp vendor/eustasy/browning/config/browning.default.php config/browning.php
```

Open your copy and fill in your details — it's a PHP file that `return`s a config array (loaded with `require` in step 2):

```php
<?php

return [
    // Mailgun API URL
    // Replace `<`example.com`>` with your verified Mailgun domain.
    'URL' => 'https://api.mailgun.net/v3/example.com',

    // Mailgun private API key.
    // Get it from https://app.mailgun.com/settings/api_security.
    'Key' => '<your-mailgun-api-key>',

    'Default' => [
        'Regards' => 'Example Support',     // Sender display name
        'ReplyTo' => 'support@example.com', // From / reply-to address
    ],

    // Optional reCAPTCHA v2 keys (see section 3).
    'Recaptcha' => [
        'SiteKey' => '<your-recaptcha-site-key>',
        'SecretKey' => '<your-recaptcha-secret-key>',
    ],
];
```

**Add `config/browning.php` to your `.gitignore` to avoid committing credentials.**

## 2. Sending email

Build a `Mailer` from your config and call `send()`:

```php
require 'vendor/autoload.php';

use Eustasy\Browning\Mailer;

$config = require 'config/browning.php';
$mailer = Mailer::fromArray($config);

$result = $mailer->send(
    'recipient@example.com', // Required: recipient address
    'Message Subject',       // Required: subject line
    'Text or HTML body',     // Required: message body
    'Sender Name',           // Optional: overrides Default.Regards
    'reply-to@example.com',  // Optional: overrides Default.ReplyTo
);

if ($result->success) {
    echo 'Email sent successfully.';
} else {
    echo 'Failed to send email: ' . $result->error;
}
```

`send()` returns a `Eustasy\Browning\Result`:

| Property | Type | Description |
| --- | --- | --- |
| `success` | `bool` | `true` if the email was accepted by Mailgun. |
| `error` | `string\|null` | Error message, or `null` on success. |

By default `error` is a friendly, user-safe message. Pass `debug: true` to get technical detail instead:

```php
$mailer = Mailer::fromArray($config, debug: true);
```

Prefer explicit wiring? Construct it directly. The constructor also accepts a custom `Transport` as its fifth argument —
that's how the tests avoid the network:

```php
$mailer = new Mailer(
    apiUrl: 'https://api.mailgun.net/v3/example.com',
    apiKey: $secret,
    fromName: 'Example Support',
    fromAddress: 'support@example.com',
);
```

## 3. Setup with reCAPTCHA

To protect a form with Google reCAPTCHA v2, add your keys under `Recaptcha` in the config —
`SiteKey` for the form, `SecretKey` for verification.

Keys come from the [Google reCAPTCHA admin console](https://www.google.com/recaptcha/admin).

## 4. CAPTCHA form

Add the widget to your HTML form, using your site key:

```html
<script src="https://www.google.com/recaptcha/api.js"></script>

<form method="post" action="">
    <input type="email" name="dear" placeholder="Recipient email" required>
    <input type="text" name="subject" placeholder="Subject" required>
    <textarea name="message" placeholder="Message" required></textarea>
    <div class="g-recaptcha" data-sitekey="your-site-key"></div>
    <button type="submit">Send</button>
</form>
```

## 5. CAPTCHA validation

Verify the response server-side before sending:

```php
use Eustasy\Browning\Mailer;
use Eustasy\Browning\Recaptcha;

$config = require 'config/browning.php';

$check = Recaptcha::fromArray($config)->verify(
    $_POST['g-recaptcha-response'] ?? null,
    $_SERVER['REMOTE_ADDR'] ?? null, // Optional: user's IP address
);

if (! $check->success) {
    echo 'reCAPTCHA failed. Please go back and try again.';
} else {
    // reCAPTCHA passed — send the email.
    $result = Mailer::fromArray($config)->send(
        $_POST['dear'] ?? '',
        $_POST['subject'] ?? '',
        $_POST['message'] ?? '',
    );
}
```

`verify()` returns a `Eustasy\Browning\RecaptchaResult` with `success` (`bool`), `errorCodes` (`string[]`), and `error` (`string|null`).
