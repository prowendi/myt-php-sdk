<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Service;

final class UserService extends AbstractService
{
    /**
     * 登录获取魔云腾Token
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postUserLoginIn(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/user/loginIn', $body, $options, ['name', 'password']);
    }

    /**
     * 同步授权
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getUserSync(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/user/sync', $query, $options, ['mytToken']);
    }
}
