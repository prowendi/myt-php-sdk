<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Service;

final class AndroidV2Service extends AbstractService
{
    /**
     * 创建安卓
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAndroidV2(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/androidV2', $body, $options, ['name', 'imageUrl', 'dns']);
    }

    /**
     * 重置安卓
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function putAndroidV2(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('PUT', '/androidV2', $body, $options, ['name']);
    }

    /**
     * 切换安卓镜像
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAndroidV2SwitchImage(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/androidV2/switchImage', $body, $options, ['name', 'imageUrl']);
    }
}
