<?php

declare(strict_types=1);

namespace Eustasy\Browning;

/**
 * A raw transport response: the body, the HTTP status, plus any
 * transport-level (e.g. cURL) error.
 */
final class Response
{
    public function __construct(
        public readonly ?string $body,
        public readonly int $errorCode = 0,
        public readonly string $errorMessage = '',
        public readonly int $statusCode = 200,
    ) {
    }

    public function failed(): bool
    {
        return $this->errorCode !== 0 || $this->body === null || $this->statusCode >= 400;
    }
}
