<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Service;

final class IscsiService extends AbstractService
{
    /**
     * 清理iSCSI残留状态（残留session、挂载、node记录）
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postMytIscsiCleanup(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/mytIscsi/cleanup', $body, $options, ['portal', 'iqn']);
    }

    /**
     * 在Target上创建磁盘映像
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postMytIscsiCreateDiskImage(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/mytIscsi/createDiskImage', $body, $options, ['portal', 'name', 'size', 'type']);
    }

    /**
     * 删除Target上的磁盘映像
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postMytIscsiDeleteDiskImage(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/mytIscsi/deleteDiskImage', $body, $options, ['portal', 'name']);
    }

    /**
     * 发现iSCSI Target
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postMytIscsiDiscover(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/mytIscsi/discover', $body, $options, ['portal']);
    }

    /**
     * 格式化iSCSI分区
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postMytIscsiFormat(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/mytIscsi/format', $body, $options, ['partition', 'fsType']);
    }

    /**
     * 查询已挂载iSCSI磁盘列表
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getMytIscsiList(array $options = []): array|string
    {
        return $this->request('GET', '/mytIscsi/list', $options);
    }

    /**
     * 获取设备可用网卡列表
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getMytIscsiListNic(array $options = []): array|string
    {
        return $this->request('GET', '/mytIscsi/listNic', $options);
    }

    /**
     * 挂载iSCSI磁盘
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postMytIscsiLogin(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/mytIscsi/login', $body, $options, ['portal', 'iqn']);
    }

    /**
     * 卸载iSCSI磁盘
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postMytIscsiLogout(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/mytIscsi/logout', $body, $options, ['portal', 'iqn']);
    }

    /**
     * 挂载iSCSI磁盘到系统并切换存储模式
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postMytIscsiMount(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/mytIscsi/mount', $body, $options, ['portal', 'iqn']);
    }

    /**
     * 对iSCSI磁盘分区
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postMytIscsiPartition(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/mytIscsi/partition', $body, $options, ['device']);
    }

    /**
     * 查询iSCSI存储状态
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getMytIscsiStatus(array $options = []): array|string
    {
        return $this->request('GET', '/mytIscsi/status', $options);
    }

    /**
     * 卸载iSCSI磁盘并恢复本地存储模式
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postMytIscsiUmount(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/mytIscsi/umount', $body, $options, ['portal', 'iqn']);
    }
}
