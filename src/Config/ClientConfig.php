<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Config;

use InvalidArgumentException;
use Myt\PhpSdk\Contract\ConnectionPoolInterface;

final class ClientConfig
{
    /**
     * @param array<string, string> $headers
     * @param array<string, mixed> $guzzle
     * @param callable|null $clientFactory fn(array $guzzleConfig): \GuzzleHttp\ClientInterface
     */
    public function __construct(
        public readonly string $baseUri,
        public readonly float $timeout = 30.0,
        public readonly float $connectTimeout = 5.0,
        public readonly array $headers = [],
        public readonly array $guzzle = [],
        public readonly ?ConnectionPoolInterface $connectionPool = null,
        public readonly mixed $clientFactory = null,
        public readonly bool $httpErrors = false,
    ) {
        if ($this->baseUri === '') {
            throw new InvalidArgumentException('baseUri cannot be empty.');
        }
        if ($this->clientFactory !== null && !is_callable($this->clientFactory)) {
            throw new InvalidArgumentException('clientFactory must be callable.');
        }
    }

    /**
     * @param array<string, mixed> $config
     */
    public static function fromArray(array $config): self
    {
        if (!isset($config['base_uri']) && !isset($config['baseUri'])) {
            throw new InvalidArgumentException('Missing required config: base_uri');
        }

        return new self(
            baseUri: (string) ($config['base_uri'] ?? $config['baseUri']),
            timeout: (float) ($config['timeout'] ?? 30.0),
            connectTimeout: (float) ($config['connect_timeout'] ?? $config['connectTimeout'] ?? 5.0),
            headers: (array) ($config['headers'] ?? []),
            guzzle: (array) ($config['guzzle'] ?? []),
            connectionPool: $config['connection_pool'] ?? $config['connectionPool'] ?? null,
            clientFactory: $config['client_factory'] ?? $config['clientFactory'] ?? null,
            httpErrors: (bool) ($config['http_errors'] ?? $config['httpErrors'] ?? false),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toGuzzleConfig(): array
    {
        $defaultHeaders = [
            'Accept' => 'application/json',
        ];

        return array_merge(
            [
                'base_uri' => $this->baseUri,
                'timeout' => $this->timeout,
                'connect_timeout' => $this->connectTimeout,
                'headers' => array_merge($defaultHeaders, $this->headers),
                'http_errors' => $this->httpErrors,
            ],
            $this->guzzle,
        );
    }
}
