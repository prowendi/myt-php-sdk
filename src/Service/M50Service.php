<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Service;

final class M50Service extends AbstractService
{
    /**
     * 查询M50 DDR信息
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getLmM50Ddr(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/lm/m50/ddr', $query, $options, []);
    }

    /**
     * 查询M50设备详情
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getLmM50Device(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/lm/m50/device', $query, $options, []);
    }

    /**
     * 查询M50 DVFS模式
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getLmM50Dvfs(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/lm/m50/dvfs', $query, $options, []);
    }

    /**
     * 设置M50 DVFS模式
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postLmM50Dvfs(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/lm/m50/dvfs', $body, $options, ['mode']);
    }

    /**
     * 查询M50频率
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getLmM50Frequency(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/lm/m50/frequency', $query, $options, []);
    }

    /**
     * 设置M50频率
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postLmM50Frequency(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/lm/m50/frequency', $body, $options, ['type', 'frequency']);
    }

    /**
     * 查询M50功耗/电压
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getLmM50Power(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/lm/m50/power', $query, $options, []);
    }
}
