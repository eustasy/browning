<?php

declare(strict_types=1);

namespace Eustasy\Browning;

/**
 * Sends an HTTP POST and returns the raw response.
 *
 * Abstracting this is what makes Mailer and Recaptcha testable without the
 * network: production code uses {@see CurlTransport}, tests use a fake.
 */
interface Transport
{
    /**
     * @param array<string, string|int|float|bool|null> $fields Form fields to POST.
     * @param string|null $basicAuth "user:password" for HTTP Basic auth, or null for none.
     */
    public function post(string $url, array $fields, ?string $basicAuth = null): Response;
}
