<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Service;

final class MacVlanService extends AbstractService
{
    /**
     * 删除网卡
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function deleteMacvlan(array $options = []): array|string
    {
        return $this->request('DELETE', '/macvlan', $options);
    }

    /**
     * 获取网卡详情
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getMacvlan(array $options = []): array|string
    {
        return $this->request('GET', '/macvlan', $options);
    }

    /**
     * 创建网卡
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postMacvlan(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/macvlan', $body, $options, ['gw', 'subnet']);
    }

    /**
     * 更新网卡
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function putMacvlan(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('PUT', '/macvlan', $body, $options, ['gw', 'subnet']);
    }
}
