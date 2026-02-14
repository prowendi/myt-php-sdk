<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Tests;

use GuzzleHttp\Psr7\Response;
use Myt\PhpSdk\ApiClient;
use Myt\PhpSdk\Contract\HttpTransportInterface;
use Myt\PhpSdk\Exception\ApiException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

final class ApiClientTest extends TestCase
{
    public function testDecodeJsonResponse(): void
    {
        $transport = new class implements HttpTransportInterface {
            public function request(string $method, string $uri, array $options = []): ResponseInterface
            {
                return new Response(200, ['Content-Type' => 'application/json'], '{"ok":true}');
            }
        };

        $client = new ApiClient($transport);
        $result = $client->request('GET', '/info');

        self::assertSame(['ok' => true], $result);
    }

    public function testThrowsApiExceptionOnHttpError(): void
    {
        $transport = new class implements HttpTransportInterface {
            public function request(string $method, string $uri, array $options = []): ResponseInterface
            {
                return new Response(500, ['Content-Type' => 'application/json'], '{"error":"boom"}');
            }
        };

        $client = new ApiClient($transport);

        $this->expectException(ApiException::class);
        $client->request('POST', '/android');
    }
}
