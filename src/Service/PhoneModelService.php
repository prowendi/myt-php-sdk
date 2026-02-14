<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Service;

final class PhoneModelService extends AbstractService
{
    /**
     * 删除本地机型数据
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function deletePhoneModel(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('DELETE', '/phoneModel', $query, $options, ['name']);
    }

    /**
     * 获取本地机型列表
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getPhoneModel(array $options = []): array|string
    {
        return $this->request('GET', '/phoneModel', $options);
    }

    /**
     * 导出本地机型数据
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postPhoneModelExport(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/phoneModel/export', $body, $options, ['name']);
    }

    /**
     * 导入机型数据
     * @param array<string, mixed> $form
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postPhoneModelImport(array $form = [], array $options = []): array|string
    {
        return $this->requestWithMultipart('POST', '/phoneModel/import', $form, $options, ['file']);
    }
}
