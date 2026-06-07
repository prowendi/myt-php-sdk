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
     * 批量切换容器镜像
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAndroidV2ChangeImage(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/androidV2/change-image', $body, $options, ['containerNames', 'image']);
    }

    /**
     * 复制云机
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getAndroidV2Copy(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/androidV2/copy', $query, $options, ['name']);
    }

    /**
     * 导出安卓云机V2
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAndroidV2Export(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/androidV2/export', $body, $options, ['name']);
    }

    /**
     * 导出安卓云机V2到共享存储
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAndroidV2ExportToOss(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/androidV2/exportToOss', $body, $options, ['name', 'ossUrl', 'bucket']);
    }

    /**
     * 导入安卓云机V2
     * @param array<string, mixed> $form
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAndroidV2Import(array $form = [], array $options = []): array|string
    {
        return $this->requestWithMultipart('POST', '/androidV2/import', $form, $options, ['file']);
    }

    /**
     * 通过URL导入安卓云机V2
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAndroidV2ImportByUrl(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/androidV2/importByUrl', $body, $options, ['url']);
    }

    /**
     * 移动云机到指定实例位
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postAndroidV2Move(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/androidV2/move', $body, $options, ['name']);
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
