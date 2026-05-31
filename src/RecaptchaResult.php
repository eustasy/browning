<?php

declare(strict_types=1);

namespace Eustasy\Browning;

/**
 * The outcome of {@see Recaptcha::verify()}.
 */
final class RecaptchaResult
{
    /**
     * @param list<string> $errorCodes The reCAPTCHA API's "error-codes", if any.
     * @param string|null $error A transport/parse error message, or null if the API responded.
     */
    public function __construct(
        public readonly bool $success,
        public readonly array $errorCodes = [],
        public readonly ?string $error = null,
    ) {
    }
}
