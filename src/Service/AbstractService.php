<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Service;

use Myt\PhpSdk\ApiClient;
use Myt\PhpSdk\Support\RequiredFieldValidator;

abstract class AbstractService
{
    public function __construct(
        protected readonly ApiClient $client,
    ) {
    }

    /**
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    protected function request(string $method, string $path, array $options = []): array|string
    {
        return $this->client->request($method, $path, $options);
    }

    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @param list<string> $requiredQuery
     * @return array<string, mixed>|string
     */
    protected function requestWithQuery(
        string $method,
        string $path,
        array $query = [],
        array $options = [],
        array $requiredQuery = [],
    ): array|string {
        RequiredFieldValidator::assertRequired($requiredQuery, $query, 'query');

        $options['query'] = array_merge($query, (array) ($options['query'] ?? []));

        return $this->request($method, $path, $options);
    }

    /**
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @param list<string> $requiredBody
     * @return array<string, mixed>|string
     */
    protected function requestWithJson(
        string $method,
        string $path,
        array $body = [],
        array $options = [],
        array $requiredBody = [],
    ): array|string {
        RequiredFieldValidator::assertRequired($requiredBody, $body, 'body');

        $options['json'] = array_merge($body, (array) ($options['json'] ?? []));

        return $this->request($method, $path, $options);
    }

    /**
     * @param array<string, mixed> $form
     * @param array<string, mixed> $options
     * @param list<string> $requiredForm
     * @return array<string, mixed>|string
     */
    protected function requestWithMultipart(
        string $method,
        string $path,
        array $form = [],
        array $options = [],
        array $requiredForm = [],
    ): array|string {
        RequiredFieldValidator::assertRequired($requiredForm, $form, 'multipart');

        $options['multipart'] = array_merge($form, (array) ($options['multipart'] ?? []));

        return $this->request($method, $path, $options);
    }
}
