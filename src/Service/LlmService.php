<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Service;

final class LlmService extends AbstractService
{
    /**
     * 导入大模型ZIP包
     * @param array<string, mixed> $form
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postLmImport(array $form = [], array $options = []): array|string
    {
        return $this->requestWithMultipart('POST', '/lm/import', $form, $options, ['file']);
    }

    /**
     * 获取系统信息
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getLmInfo(array $options = []): array|string
    {
        return $this->request('GET', '/lm/info', $options);
    }

    /**
     * 删除本地大模型
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function deleteLmLocal(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('DELETE', '/lm/local', $query, $options, ['name']);
    }

    /**
     * 获取本地大模型列表
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getLmLocal(array $options = []): array|string
    {
        return $this->request('GET', '/lm/local', $options);
    }

    /**
     * 获取模型运行状态
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getLmModels(array $options = []): array|string
    {
        return $this->request('GET', '/lm/models', $options);
    }

    /**
     * 重置设备
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postLmReset(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/lm/reset', $body, $options, ['type', 'device_id']);
    }

    /**
     * 启动 LLM 服务
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postLmServerStart(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/lm/server/start', $body, $options, ['models']);
    }

    /**
     * 停止 LLM 服务
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postLmServerStop(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/lm/server/stop', $body, $options, []);
    }

    /**
     * 设置工作模式
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postLmWorkMode(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/lm/workMode', $body, $options, ['device_id', 'chip_id', 'work_mode']);
    }

    /**
     * 模型推理对话补全
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postV1ChatCompletions(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/v1/chat/completions', $body, $options, ['model', 'messages']);
    }
}
