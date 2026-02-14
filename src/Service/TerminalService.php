<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Service;

final class TerminalService extends AbstractService
{
    /**
     * 连接容器终端-IP:8000/container/exec
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getLinkExec(array $options = []): array|string
    {
        return $this->request('GET', '/link/exec', $options);
    }

    /**
     * 连接设备SSH-IP:8000/ssh
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getLinkSsh(array $options = []): array|string
    {
        return $this->request('GET', '/link/ssh', $options);
    }

    /**
     * 修改SSH登录用户的密码
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postLinkSshChangePwd(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/link/ssh/changePwd', $body, $options, ['password']);
    }

    /**
     * 开关SSH服务
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postLinkSshEnable(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/link/ssh/enable', $body, $options, []);
    }

    /**
     * 开关SSH root登录
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postLinkSshSwitchRoot(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/link/ssh/switchRoot', $body, $options, []);
    }
}
