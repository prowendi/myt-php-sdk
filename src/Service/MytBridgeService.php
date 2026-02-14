<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Service;

final class MytBridgeService extends AbstractService
{
    /**
     * 删除myt_bridge网卡
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function deleteMytBridge(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('DELETE', '/mytBridge', $query, $options, ['name']);
    }

    /**
     * 获取myt_bridge网卡列表
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getMytBridge(array $options = []): array|string
    {
        return $this->request('GET', '/mytBridge', $options);
    }

    /**
     * 创建myt_bridge网卡
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postMytBridge(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/mytBridge', $body, $options, ['customName', 'cidr']);
    }

    /**
     * 更新myt_bridge网卡
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function putMytBridge(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('PUT', '/mytBridge', $body, $options, ['name', 'newCidr']);
    }
}
