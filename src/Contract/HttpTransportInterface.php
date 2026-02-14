<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Contract;

use Psr\Http\Message\ResponseInterface;

interface HttpTransportInterface
{
    /**
     * @param array<string, mixed> $options
     */
    public function request(string $method, string $uri, array $options = []): ResponseInterface;
}
