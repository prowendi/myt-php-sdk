<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Android;

use InvalidArgumentException;

/**
 * 魔云腾 安卓容器端口计算助手（MYT_ANDROID_API）。
 *
 * 桥接模式：每个容器端口固定。
 * 非桥接模式：端口 = 30000 + (实例位 index - 1) * 100 + 功能偏移。
 *
 * 仅 HTTP API 端口（offset=1 / 桥接 9082）用于本 SDK 的 {@see AndroidApiClient}；
 * 其余端口（ADB/RPA/投屏/控制/摄像头/WebRTC）一并提供，便于上层拼装。
 */
final class AndroidPort
{
    /** 桥接模式固定端口 */
    public const BRIDGE_ADB = 5555;
    public const BRIDGE_API = 9082;
    public const BRIDGE_RPA = 9083;
    public const BRIDGE_SCREEN = 10000;
    public const BRIDGE_CONTROL = 10001;
    public const BRIDGE_CAMERA_TCP = 10006;
    public const BRIDGE_CAMERA_UDP = 10007;
    public const BRIDGE_WEBRTC_TCP = 10008;
    public const BRIDGE_WEBRTC_UDP = 10008;

    /** 非桥接模式：基址、步长与各功能偏移 */
    public const NON_BRIDGE_BASE = 30000;
    public const NON_BRIDGE_STEP = 100;
    public const OFFSET_ADB = 0;
    public const OFFSET_API = 1;
    public const OFFSET_RPA = 2;
    public const OFFSET_SCREEN = 3;
    public const OFFSET_CONTROL = 4;
    public const OFFSET_CAMERA_TCP = 5;
    public const OFFSET_CAMERA_UDP = 6;
    public const OFFSET_WEBRTC_TCP = 7;
    public const OFFSET_WEBRTC_UDP = 8;

    /**
     * 非桥接模式下按实例位与功能偏移计算端口。
     */
    public static function nonBridge(int $index, int $offset): int
    {
        if ($index < 1) {
            throw new InvalidArgumentException('index must be >= 1');
        }
        if ($offset < 0 || $offset > self::OFFSET_WEBRTC_UDP) {
            throw new InvalidArgumentException('offset out of range (0-8)');
        }

        return self::NON_BRIDGE_BASE + ($index - 1) * self::NON_BRIDGE_STEP + $offset;
    }

    /** 安卓 HTTP API 端口（桥接固定 9082，非桥接按 index 计算）。 */
    public static function api(int $index = 1, bool $bridge = false): int
    {
        return $bridge ? self::BRIDGE_API : self::nonBridge($index, self::OFFSET_API);
    }

    /** ADB 端口。 */
    public static function adb(int $index = 1, bool $bridge = false): int
    {
        return $bridge ? self::BRIDGE_ADB : self::nonBridge($index, self::OFFSET_ADB);
    }

    /** 安卓 RPA 端口。 */
    public static function rpa(int $index = 1, bool $bridge = false): int
    {
        return $bridge ? self::BRIDGE_RPA : self::nonBridge($index, self::OFFSET_RPA);
    }

    /** WebRTC TCP 端口（投屏播放器用）。 */
    public static function webrtcTcp(int $index = 1, bool $bridge = false): int
    {
        return $bridge ? self::BRIDGE_WEBRTC_TCP : self::nonBridge($index, self::OFFSET_WEBRTC_TCP);
    }

    /** WebRTC UDP 端口（投屏播放器用）。 */
    public static function webrtcUdp(int $index = 1, bool $bridge = false): int
    {
        return $bridge ? self::BRIDGE_WEBRTC_UDP : self::nonBridge($index, self::OFFSET_WEBRTC_UDP);
    }

    /** 拼装安卓 HTTP API 的 base_uri，例如 http://192.168.30.2:9082 。 */
    public static function apiBaseUri(string $ip, int $index = 1, bool $bridge = false): string
    {
        if ($ip === '') {
            throw new InvalidArgumentException('ip cannot be empty.');
        }
        // 防止 $ip 携带 路径/认证/查询 等字符破坏 base_uri（避免被改写到非预期主机）。
        if (preg_match('#[/@?\#\s\\\\]#', $ip) === 1) {
            throw new InvalidArgumentException('ip must be a bare host/IP without "/", "@", "?", "#", whitespace or backslash.');
        }

        return sprintf('http://%s:%d', $ip, self::api($index, $bridge));
    }
}
