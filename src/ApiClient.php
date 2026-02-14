<?php

declare(strict_types=1);

namespace Myt\PhpSdk;

use Myt\PhpSdk\Contract\HttpTransportInterface;
use Myt\PhpSdk\Exception\ApiException;
use Myt\PhpSdk\Support\MultipartBuilder;
use Throwable;

final class ApiClient
{
    public function __construct(
        private readonly HttpTransportInterface $transport,
    ) {
    }

    /**
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function request(string $method, string $uri, array $options = []): array|string
    {
        $resourcesToClose = [];

        if (isset($options['multipart']) && is_array($options['multipart']) && !array_is_list($options['multipart'])) {
            [$options['multipart'], $resourcesToClose] = MultipartBuilder::build($options['multipart']);

            if (isset($options['headers']['Content-Type'])) {
                unset($options['headers']['Content-Type']);
            }
        }

        try {
            $response = $this->transport->request($method, $uri, $options);
            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();

            if ($statusCode >= 400) {
                throw new ApiException(
                    message: sprintf('HTTP %d error for %s %s', $statusCode, strtoupper($method), $uri),
                    statusCode: $statusCode,
                    responseBody: $body,
                    headers: $response->getHeaders(),
                    method: strtoupper($method),
                    uri: $uri,
                );
            }

            if ($body === '') {
                return [];
            }

            if ($this->isJsonResponse($response->getHeaderLine('Content-Type'), $body)) {
                $decoded = json_decode($body, true, 512, JSON_BIGINT_AS_STRING);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new ApiException(
                        message: sprintf('Invalid JSON response for %s %s: %s', strtoupper($method), $uri, json_last_error_msg()),
                        statusCode: $statusCode,
                        responseBody: $body,
                        headers: $response->getHeaders(),
                        method: strtoupper($method),
                        uri: $uri,
                    );
                }

                return is_array($decoded) ? $decoded : ['value' => $decoded];
            }

            return $body;
        } catch (ApiException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new ApiException(
                message: sprintf('Request failed for %s %s: %s', strtoupper($method), $uri, $e->getMessage()),
                method: strtoupper($method),
                uri: $uri,
                previous: $e,
            );
        } finally {
            foreach ($resourcesToClose as $resource) {
                if (is_resource($resource)) {
                    fclose($resource);
                }
            }
        }
    }

    private function isJsonResponse(string $contentType, string $body): bool
    {
        if ($contentType !== '' && str_contains(strtolower($contentType), 'json')) {
            return true;
        }

        $trimmed = ltrim($body);

        return $trimmed !== '' && ($trimmed[0] === '{' || $trimmed[0] === '[');
    }
}
