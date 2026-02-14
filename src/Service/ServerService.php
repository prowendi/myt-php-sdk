<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Service;

final class ServerService extends AbstractService
{
    /**
     * 重启设备
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postServerDeviceReboot(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/server/device/reboot', $body, $options, []);
    }

    /**
     * 清空设备磁盘数据(高危操作！)
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postServerDeviceReset(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/server/device/reset', $body, $options, []);
    }

    /**
     * 开启和屏蔽dockerApi 2375端口
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postServerDockerApi(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/server/dockerApi', $body, $options, []);
    }

    /**
     * 获取主机网络信息
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getServerNetwork(array $options = []): array|string
    {
        return $this->request('GET', '/server/network', $options);
    }

    /**
     * 更新服务
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getServerUpgrade(array $options = []): array|string
    {
        return $this->request('GET', '/server/upgrade', $options);
    }

    /**
     * 通过上传sdk更新服务
     * @param array<string, mixed> $form
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postServerUpgradeUpload(array $form = [], array $options = []): array|string
    {
        return $this->requestWithMultipart('POST', '/server/upgrade/upload', $form, $options, ['file']);
    }
}
