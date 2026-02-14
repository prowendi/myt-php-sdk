<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Exception;

use RuntimeException;

class ApiException extends RuntimeException
{
    /**
     * @param array<string, string[]> $headers
     */
    public function __construct(
        string $message,
        public readonly int $statusCode = 0,
        public readonly string $responseBody = '',
        public readonly array $headers = [],
        public readonly string $method = '',
        public readonly string $uri = '',
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $statusCode, $previous);
    }
}
