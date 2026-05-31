<?php

declare(strict_types=1);

namespace Eustasy\Browning;

/**
 * Sends email through the Mailgun HTTP API.
 *
 * Config is injected (no globals); the cURL transport can be swapped for tests.
 */
final class Mailer
{
    /**
     * @param string $apiUrl Mailgun base URL, e.g. https://api.mailgun.net/v3/<domain>
     * @param string $apiKey Mailgun private API key.
     * @param string $fromName Default sender display name.
     * @param string $fromAddress Default sender / reply-to address (matches the API domain).
     * @param bool $debug When true, error messages carry technical detail instead of a friendly one.
     */
    public function __construct(
        private readonly string $apiUrl,
        private readonly string $apiKey,
        private readonly string $fromName,
        private readonly string $fromAddress,
        private readonly Transport $transport = new CurlTransport(),
        private readonly bool $debug = false,
    ) {
    }

    /**
     * Build from the array shape shipped in config/browning.default.php.
     *
     * @param array{URL: string, Key: string, Default: array{Regards: string, ReplyTo: string}} $config
     */
    public static function fromArray(array $config, ?Transport $transport = null, bool $debug = false): self
    {
        return new self(
            $config['URL'],
            $config['Key'],
            $config['Default']['Regards'],
            $config['Default']['ReplyTo'],
            $transport ?? new CurlTransport(),
            $debug,
        );
    }

    public function send(
        string $to,
        string $subject,
        string $message,
        ?string $fromName = null,
        ?string $fromAddress = null,
    ): Result {
        $missing = $this->missingFields($to, $subject, $message);
        if ($missing !== []) {
            $plural = count($missing) > 1 ? 's' : '';

            return new Result(false, 'Please provide the following required field' . $plural . ': ' . implode(', ', $missing) . '.');
        }

        $from = ($fromName ?? $this->fromName) . ' <' . ($fromAddress ?? $this->fromAddress) . '>';

        $response = $this->transport->post(
            $this->apiUrl . '/messages',
            [
                'from' => $from,
                'to' => $to,
                'subject' => $subject,
                'text' => $message,
            ],
            'api:' . $this->apiKey,
        );

        if ($response->errorCode !== 0) {
            return new Result(false, $this->explain(
                'Unable to send email at this time. Please try again later.',
                $response->errorCode . ' Error: ' . $response->errorMessage,
            ));
        }

        if ($response->body === null || $response->body === '') {
            return new Result(false, $this->explain(
                'Unable to send email at this time. Please try again later.',
                'No response received from mail server.',
            ));
        }

        if ($response->body === 'Forbidden') {
            return new Result(false, $this->explain(
                'This website is unable to send email.',
                'Forbidden: Check your Mailgun configuration and API key.',
            ));
        }

        return new Result(true);
    }

    /**
     * @return list<string> Human-readable labels of any missing required fields.
     */
    private function missingFields(string $to, string $subject, string $message): array
    {
        $missing = [];
        if ($to === '') {
            $missing[] = 'recipient email address';
        }
        if ($subject === '') {
            $missing[] = 'subject';
        }
        if ($message === '') {
            $missing[] = 'message body';
        }

        return $missing;
    }

    private function explain(string $friendly, string $technical): string
    {
        return $this->debug ? $technical : $friendly;
    }
}
