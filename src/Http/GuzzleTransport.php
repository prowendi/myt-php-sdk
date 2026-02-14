<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Http;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Myt\PhpSdk\Config\ClientConfig;
use Myt\PhpSdk\Contract\HttpTransportInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

final class GuzzleTransport implements HttpTransportInterface
{
    private readonly ClientInterface $defaultClient;

    public function __construct(
        private readonly ClientConfig $config,
        ?ClientInterface $client = null,
    ) {
        $this->defaultClient = $client ?? $this->buildClient($this->config->toGuzzleConfig());
    }

    /**
     * @param array<string, mixed> $options
     */
    public function request(string $method, string $uri, array $options = []): ResponseInterface
    {
        $pool = $this->config->connectionPool;
        if ($pool !== null) {
            $client = $pool->acquire();
            try {
                return $client->request($method, $uri, $options);
            } finally {
                $pool->release($client);
            }
        }

        return $this->defaultClient->request($method, $uri, $options);
    }

    /**
     * @param array<string, mixed> $guzzleConfig
     */
    private function buildClient(array $guzzleConfig): ClientInterface
    {
        if ($this->config->clientFactory !== null) {
            $client = ($this->config->clientFactory)($guzzleConfig);
            if (!$client instanceof ClientInterface) {
                throw new RuntimeException('clientFactory must return GuzzleHttp\\ClientInterface.');
            }

            return $client;
        }

        return new Client($guzzleConfig);
    }
}
