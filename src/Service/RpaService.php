<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Service;

final class RpaService extends AbstractService
{
    /**
     * 检查RPA连接
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postRpaCheck(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/rpa/check', $body, $options, ['name']);
    }

    /**
     * 单击
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postRpaClick(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/rpa/click', $body, $options, ['name', 'x', 'y']);
    }

    /**
     * 连接RPA服务
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postRpaConnect(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/rpa/connect', $body, $options, ['name']);
    }

    /**
     * 断开RPA连接
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postRpaDisconnect(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/rpa/disconnect', $body, $options, ['name']);
    }

    /**
     * 双击
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postRpaDoubleClick(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/rpa/double_click', $body, $options, ['name', 'x', 'y']);
    }

    /**
     * 获取UI节点树
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postRpaDumpUi(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/rpa/dump_ui', $body, $options, ['name']);
    }

    /**
     * 屏幕是否亮屏
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postRpaIsScreenOn(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/rpa/is_screen_on', $body, $options, ['name']);
    }

    /**
     * 按键
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postRpaKey(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/rpa/key', $body, $options, ['name', 'code']);
    }

    /**
     * 长按
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postRpaLongPress(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/rpa/long_press', $body, $options, ['name', 'x', 'y', 'duration']);
    }

    /**
     * 打开App
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postRpaOpenApp(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/rpa/open_app', $body, $options, ['name', 'pkg']);
    }

    /**
     * 屏幕旋转角度
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postRpaRotation(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/rpa/rotation', $body, $options, ['name']);
    }

    /**
     * 截屏
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postRpaScreenshot(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/rpa/screenshot', $body, $options, ['name']);
    }

    /**
     * 执行Shell命令
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postRpaShell(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/rpa/shell', $body, $options, ['name', 'cmd']);
    }

    /**
     * 停止App
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postRpaStopApp(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/rpa/stop_app', $body, $options, ['name', 'pkg']);
    }

    /**
     * 滑动
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postRpaSwipe(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/rpa/swipe', $body, $options, ['name', 'x1', 'y1', 'x2', 'y2']);
    }

    /**
     * 多点触控
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postRpaTouch(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/rpa/touch', $body, $options, ['name', 'action', 'x', 'y']);
    }

    /**
     * 输入文本
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postRpaType(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/rpa/type', $body, $options, ['name', 'text']);
    }
}
