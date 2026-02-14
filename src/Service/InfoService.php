<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Service;

final class InfoService extends AbstractService
{
    /**
     * 获取当前API版本信息
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getInfo(array $options = []): array|string
    {
        return $this->request('GET', '/info', $options);
    }

    /**
     * 当前设备基本信息
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getInfoDevice(array $options = []): array|string
    {
        return $this->request('GET', '/info/device', $options);
    }
}
