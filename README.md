# Browning: A Mailgun Script

##### Browning is a tiny PHP function to send emails with Mailgun, that uses CURL instead of Mailgun's (slightly porky) library.

[![Normal](https://github.com/eustasy/browning/actions/workflows/normal.yml/badge.svg)](https://github.com/eustasy/browning/actions/workflows/normal.yml)
[![Code Climate](https://codeclimate.com/github/eustasy/browning/badges/gpa.svg)](https://codeclimate.com/github/eustasy/browning)

## 1. Setup with Mailgun

Make sure you have the following packages installed. CURL is required.

```
libmagic-dev php-dev libcurl3 php-curl
```

Copy `_settings/browning.default.php` to `_settings/browning.custom.php` and fill in your details:

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

## 2. Code

Load the settings and function, then call `Browning()`:

```php
require '_settings/browning.default.php';
require '_settings/browning.custom.php';
require_once '_functions/browning/function.browning.php';

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
|---|---|---|
| `Success` | `bool` | `true` if the email was accepted by Mailgun. |
| `Error` | `string\|false` | Error message, or `false` on success. With `$Debug = true`, error messages include technical detail. |

## 3. Setup with reCAPTCHA

To protect your contact form with Google reCAPTCHA v2, add your site and secret keys to `_settings/browning.custom.php`:

```php
$Recaptcha['Enable'] = true;
$Recaptcha['SiteKey'] = 'your-site-key';
$Recaptcha['SecretKey'] = 'your-secret-key';
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
require_once '_functions/browning/function.recaptcha.verify.php';

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
    require_once '_functions/browning/function.browning.php';
    $Mail = Browning($_POST['dear'], $_POST['subject'], $_POST['message']);
}
```
