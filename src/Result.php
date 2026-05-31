<?php

declare(strict_types=1);

namespace Eustasy\Browning;

/**
 * The outcome of {@see Mailer::send()}.
 */
final class Result
{
    public function __construct(
        public readonly bool $success,
        public readonly ?string $error = null,
    ) {
    }
}
