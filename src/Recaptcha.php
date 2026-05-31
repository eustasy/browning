<?php

declare(strict_types=1);

namespace Eustasy\Browning;

/**
 * Verifies a Google reCAPTCHA v2 response token via the siteverify API.
 */
final class Recaptcha
{
    private const ENDPOINT = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * @param string $secret The reCAPTCHA secret key.
     * @param bool $debug When true, transport error messages carry technical detail.
     * @param string $endpoint Override the siteverify URL (for testing).
     */
    public function __construct(
        private readonly string $secret,
        private readonly Transport $transport = new CurlTransport(),
        private readonly bool $debug = false,
        private readonly string $endpoint = self::ENDPOINT,
    ) {
    }

    /**
     * Build from the array shape shipped in config/browning.default.php.
     *
     * @param array{Recaptcha: array{SecretKey: string}} $config
     */
    public static function fromArray(array $config, ?Transport $transport = null, bool $debug = false): self
    {
        return new self($config['Recaptcha']['SecretKey'], $transport ?? new CurlTransport(), $debug);
    }

    public function verify(?string $response, ?string $remoteIp = null): RecaptchaResult
    {
        $raw = $this->transport->post($this->endpoint, [
            'secret' => $this->secret,
            'response' => $response,
            'remoteip' => $remoteIp,
        ]);

        if ($raw->errorCode !== 0) {
            return new RecaptchaResult(false, [], $this->debug
                ? $raw->errorCode . ' Error: ' . $raw->errorMessage
                : 'Unable to verify reCAPTCHA at this time.');
        }

        $decoded = $raw->body !== null ? json_decode($raw->body, true) : null;
        if (! is_array($decoded)) {
            return new RecaptchaResult(false, [], 'Invalid response from reCAPTCHA server.');
        }

        $errorCodes = [];
        if (isset($decoded['error-codes']) && is_array($decoded['error-codes'])) {
            foreach ($decoded['error-codes'] as $code) {
                $errorCodes[] = (string) $code;
            }
        }

        return new RecaptchaResult(! empty($decoded['success']), $errorCodes);
    }
}
