<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Service;

final class AuthService extends AbstractService
{
    /**
     * 关闭接口认证
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAuthClose(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/auth/close', $body, $options, []);
    }

    /**
     * 修改认证密码(默认用户名admin)
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAuthPassword(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/auth/password', $body, $options, ['newPassword', 'confirmPassword']);
    }
}
