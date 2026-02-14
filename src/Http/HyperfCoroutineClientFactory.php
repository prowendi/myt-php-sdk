<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Http;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use RuntimeException;

final class HyperfCoroutineClientFactory
{
    /**
     * @param array<string, mixed> $guzzleConfig
     */
    public static function create(array $guzzleConfig): ClientInterface
    {
        $handlerClass = '\\Hyperf\\Guzzle\\CoroutineHandler';

        if (!class_exists($handlerClass)) {
            throw new RuntimeException(
                'Class Hyperf\\Guzzle\\CoroutineHandler not found. Install hyperf/guzzle first.'
            );
        }

        $guzzleConfig['handler'] = new $handlerClass();

        return new Client($guzzleConfig);
    }
}
