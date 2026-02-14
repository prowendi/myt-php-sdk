<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Service;

final class BackupService extends AbstractService
{
    /**
     * 删除备份压缩包文件
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function deleteBackup(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('DELETE', '/backup', $query, $options, ['name']);
    }

    /**
     * 获取备份压缩包文件列表
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getBackup(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/backup', $query, $options, []);
    }

    /**
     * 下载备份压缩包文件
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getBackupDownload(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/backup/download', $query, $options, ['name']);
    }
}
