<?php

declare(strict_types=1);

namespace Eustasy\Browning\Tests\Support;

use Eustasy\Browning\Response;
use Eustasy\Browning\Transport;
use RuntimeException;

/**
 * An in-memory {@see Transport} that records every call and returns a canned
 * response — no sockets, no network. This is the testability payoff of the DI
 * design: Mailer/Recaptcha logic is exercised without an HTTP server.
 */
final class FakeTransport implements Transport
{
    /** @var list<array{url: string, fields: array<string, string|int|float|bool|null>, basicAuth: string|null}> */
    public array $calls = [];

    public function __construct(private Response $response)
    {
    }

    public function post(string $url, array $fields, ?string $basicAuth = null): Response
    {
        $this->calls[] = ['url' => $url, 'fields' => $fields, 'basicAuth' => $basicAuth];

        return $this->response;
    }

    /**
     * @return array{url: string, fields: array<string, string|int|float|bool|null>, basicAuth: string|null}
     */
    public function lastCall(): array
    {
        $call = end($this->calls);
        if ($call === false) {
            throw new RuntimeException('No transport calls were recorded.');
        }

        return $call;
    }
}
