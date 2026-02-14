<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Service;

final class AndroidService extends AbstractService
{
    /**
     * 删除安卓
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function deleteAndroid(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('DELETE', '/android', $query, $options, ['name']);
    }

    /**
     * 获取安卓云机列表
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getAndroid(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/android', $query, $options, []);
    }

    /**
     * 创建安卓
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAndroid(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/android', $body, $options, ['name', 'imageUrl', 'dns']);
    }

    /**
     * 重置安卓
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function putAndroid(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('PUT', '/android', $body, $options, ['name']);
    }

    /**
     * 删除机型备份
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function deleteAndroidBackupModel(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('DELETE', '/android/backup/model', $query, $options, ['name']);
    }

    /**
     * 获取机型备份列表
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getAndroidBackupModel(array $options = []): array|string
    {
        return $this->request('GET', '/android/backup/model', $options);
    }

    /**
     * 备份机型数据(将V3镜像创建的云机里的机型数据完整备份)
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAndroidBackupModel(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/android/backup/model', $body, $options, ['name', 'suffix']);
    }

    /**
     * 导出机型备份数据
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getAndroidBackupModelExport(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/android/backup/modelExport', $query, $options, ['name']);
    }

    /**
     * 导入备份机型数据
     * @param array<string, mixed> $form
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAndroidBackupModelImport(array $form = [], array $options = []): array|string
    {
        return $this->requestWithMultipart('POST', '/android/backup/modelImport', $form, $options, ['file']);
    }

    /**
     * 获取国家代码列表
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getAndroidCountryCode(array $options = []): array|string
    {
        return $this->request('GET', '/android/countryCode', $options);
    }

    /**
     * 安卓云机执行命令
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAndroidExec(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/android/exec', $body, $options, ['name', 'command']);
    }

    /**
     * 导出安卓云机
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAndroidExport(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/android/export', $body, $options, ['name']);
    }

    /**
     * 删除本地镜像
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function deleteAndroidImage(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('DELETE', '/android/image', $query, $options, []);
    }

    /**
     * 获取本地镜像列表
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getAndroidImage(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/android/image', $query, $options, []);
    }

    /**
     * 下载导出后的安卓镜像包
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getAndroidImageDownload(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/android/image/download', $query, $options, ['filename']);
    }

    /**
     * 导出安卓镜像
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAndroidImageExport(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/android/image/export', $body, $options, ['imageUrl']);
    }

    /**
     * 导入安卓镜像
     * @param array<string, mixed> $form
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAndroidImageImport(array $form = [], array $options = []): array|string
    {
        return $this->requestWithMultipart('POST', '/android/image/import', $form, $options, ['file']);
    }

    /**
     * 删除本地镜像压缩包
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function deleteAndroidImageTar(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('DELETE', '/android/imageTar', $query, $options, []);
    }

    /**
     * 获取本地镜像压缩包列表
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getAndroidImageTar(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/android/imageTar', $query, $options, []);
    }

    /**
     * 导入安卓云机
     * @param array<string, mixed> $form
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAndroidImport(array $form = [], array $options = []): array|string
    {
        return $this->requestWithMultipart('POST', '/android/import', $form, $options, ['file']);
    }

    /**
     * 设置Macvlan
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAndroidMacvlan(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/android/macvlan', $body, $options, ['gw', 'subnet']);
    }

    /**
     * 获取机型列表
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getAndroidPhoneModel(array $options = []): array|string
    {
        return $this->request('GET', '/android/phoneModel', $options);
    }

    /**
     * 清理所有未被使用镜像
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAndroidPruneImages(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/android/pruneImages', $body, $options, []);
    }

    /**
     * 拉取安卓镜像
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAndroidPullImage(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/android/pullImage', $body, $options, ['imageUrl']);
    }

    /**
     * 修改云机容器名称
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAndroidRename(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/android/rename', $body, $options, ['name', 'newName']);
    }

    /**
     * 重启安卓
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAndroidRestart(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/android/restart', $body, $options, ['name']);
    }

    /**
     * 启动安卓
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAndroidStart(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/android/start', $body, $options, ['name']);
    }

    /**
     * 关闭安卓
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAndroidStop(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/android/stop', $body, $options, ['name']);
    }

    /**
     * 切换安卓镜像
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAndroidSwitchImage(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/android/switchImage', $body, $options, ['name', 'imageUrl']);
    }

    /**
     * 切换机型
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAndroidSwitchModel(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/android/switchModel', $body, $options, ['name']);
    }
}
