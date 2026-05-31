# Browning: A Mailgun Script (v0.30)

**Browning is a tiny PHP function to send emails with Mailgun, that uses CURL instead of Mailgun's (slightly porky) library.**

[![Normal (PHP)](https://github.com/eustasy/browning/actions/workflows/php.yml/badge.svg)](https://github.com/eustasy/browning/actions/workflows/php.yml)
[![Normal (Markdown)](https://github.com/eustasy/browning/actions/workflows/md.yml/badge.svg)](https://github.com/eustasy/browning/actions/workflows/md.yml)
[![Normal (Security)](https://github.com/eustasy/browning/actions/workflows/security.yml/badge.svg)](https://github.com/eustasy/browning/actions/workflows/security.yml)
[![Test (PHP)](https://github.com/eustasy/browning/actions/workflows/test-php.yml/badge.svg)](https://github.com/eustasy/browning/actions/workflows/test-php.yml)
[![CodeQL](https://github.com/eustasy/browning/actions/workflows/github-code-scanning/codeql/badge.svg)](https://github.com/eustasy/browning/actions/workflows/github-code-scanning/codeql)
[![Maintainability](https://qlty.sh/gh/eustasy/projects/browning/maintainability.svg)](https://qlty.sh/gh/eustasy/projects/browning)
[![Code Coverage](https://qlty.sh/gh/eustasy/projects/browning/coverage.svg)](https://qlty.sh/gh/eustasy/projects/browning)

## Installation

```sh
composer require eustasy/browning
```

Then include the Composer autoloader in your project:

```php
require 'vendor/autoload.php';
```

This makes both `Browning()` and `Recaptcha_Verify()` available without any manual `require` calls.

## 1. Setup with Mailgun

Make sure you have the following packages installed. CURL is required.

```text
libmagic-dev php-dev libcurl3 php-curl
```

Copy the bundled config template to a location outside `vendor/`, for example `config/browning.php`:

```sh
cp vendor/eustasy/browning/config/browning.default.php config/browning.php
```

Then open your copy and fill in your details:

```php
// Mailgun API URL — replace example.com with your verified Mailgun domain
$Browning['URL'] = 'https://api.mailgun.net/v3/example.com';

// Mailgun API Key — found at https://mailgun.com/cp (use the private API key, not the public key)
$Browning['Key'] = 'key-123456-abcdefg-789012-abc-34567';

// Display name for the sender
$Browning['Default']['Regards'] = 'Example Support';

// Reply-to address — should match the domain in your API URL
$Browning['Default']['ReplyTo'] = 'support@example.com';
```

**Add `config/browning.php` to your `.gitignore` to avoid committing credentials.**

## 2. Code

Load the settings and function, then call `Browning()`:

```php
require 'vendor/autoload.php';
require 'config/browning.php';

$Mail = Browning(
    'recipient@example.com', // Required: recipient address
    'Message Subject',       // Required: subject line
    'Text or HTML body',     // Required: message body
    'Sender Name',           // Optional: overrides $Browning['Default']['Regards']
    'reply-to@example.com',  // Optional: overrides $Browning['Default']['ReplyTo']
    false                    // Optional: set true to enable debug output
);

if ($Mail['Success']) {
    echo 'Email sent successfully.';
} else {
    echo 'Failed to send email: ' . $Mail['Error'];
}
```

The function always returns an array with two keys:

| Key | Type | Description |
| --- | --- | --- |
| `Success` | `bool` | `true` if the email was accepted by Mailgun. |
| `Error` | `string\|false` | Error message, or `false` on success. With `$Debug = true`, error messages include technical detail. |

## 3. Setup with reCAPTCHA

To protect your contact form with Google reCAPTCHA v2, add your site and secret keys to your config file (e.g. `config/browning.php`):

```php
$Recaptcha['Enable'] = true;
$Recaptcha['SiteKey'] = '0123456789abcdefghijklmnopqrstuvwxyz';
$Recaptcha['SecretKey'] = '0123456789abcdefghijklmnopqrstuvwxyz';
```

Keys can be obtained from the [Google reCAPTCHA admin console](https://www.google.com/recaptcha/admin).

## 4. CAPTCHA Form

Add the reCAPTCHA widget to your HTML form. Include the reCAPTCHA script and add the `g-recaptcha` div with your site key:

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

## 5. CAPTCHA Validation

Before sending the email, verify the reCAPTCHA response server-side:

```php
$Recaptcha['Validity'] = Recaptcha_Verify(
    $Recaptcha['SecretKey'],
    $_POST['g-recaptcha-response'],
    $_SERVER['REMOTE_ADDR'] // Optional: user's IP address
);

if (!$Recaptcha['Validity']['Success']) {
    echo 'reCAPTCHA failed. Please go back and try again.';
    if (!empty($Recaptcha['Validity']['Error'])) {
        echo ' ' . $Recaptcha['Validity']['Error'];
    }
} else {
    // reCAPTCHA passed — send the email
    $Mail = Browning($_POST['dear'], $_POST['subject'], $_POST['message']);
}
```
