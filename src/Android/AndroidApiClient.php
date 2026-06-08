<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Android;

use Myt\PhpSdk\ApiClient;
use Myt\PhpSdk\Config\ClientConfig;
use Myt\PhpSdk\Contract\HttpTransportInterface;
use Myt\PhpSdk\Http\GuzzleTransport;
use Myt\PhpSdk\Service\AbstractService;

/**
 * 魔云腾「安卓容器」API 客户端（MYT_ANDROID_API，文档 v3，共 41 个接口）。
 *
 * 这是针对【单个安卓容器】的本机控制接口，独立于 api-1.json（宿主机管理接口，
 * 形如 http://127.0.0.1:9511，由 {@see \Myt\PhpSdk\MytSdk} 封装）。两者 base_uri、
 * 路由、用途均不同，互不冲突。
 *
 * 调用地址：http://{设备IP}:{端口}
 *  - 桥接模式：API 端口固定 9082
 *  - 非桥接模式：API 端口 = 30000 + (实例位 index - 1) * 100 + 1
 * 端口可用 {@see AndroidPort} 计算，或直接用 {@see self::forContainer()} 便捷构造。
 *
 * 返回约定：多数接口返回信封 JSON {"code":200,"msg":"ok","data":{...}}；
 * 下载/截图返回图片或文件二进制（字符串）；个别上传接口返回纯文本。
 * 本客户端沿用 ApiClient 规则原样返回 array|string。
 * 注意：业务失败以 HTTP 200 + code=201/202（error/reason 字段）返回，不会抛
 * ApiException——请自行检查 $res['code']。仅当 HTTP >= 400 才抛 ApiException。
 *
 * 单一用途接口已内置 cmd（如 setClipboard 内置 cmd=2），且内置参数不可被调用方覆盖；
 * 多形态接口（adb / camera / background / googleAdId / installModule）的 cmd 由调用方传入。
 */
final class AndroidApiClient extends AbstractService
{
    /**
     * 用配置数组或 ClientConfig 创建。base_uri 必填，形如 http://192.168.30.2:9082 。
     *
     * @param array<string, mixed>|ClientConfig $config
     */
    public static function create(array|ClientConfig $config, ?HttpTransportInterface $transport = null): self
    {
        $clientConfig = is_array($config) ? ClientConfig::fromArray($config) : $config;

        return new self(new ApiClient($transport ?? new GuzzleTransport($clientConfig)));
    }

    /**
     * 按「设备 IP + 实例位」便捷创建（自动计算 API 端口并设置 base_uri）。
     *
     * @param array<string, mixed> $config 其它配置（timeout、headers 等）；base_uri 会被覆盖
     */
    public static function forContainer(
        string $ip,
        int $index = 1,
        bool $bridge = false,
        array $config = [],
        ?HttpTransportInterface $transport = null,
    ): self {
        $config['base_uri'] = AndroidPort::apiBaseUri($ip, $index, $bridge);

        return self::create($config, $transport);
    }

    /**
     * 内部：发起 GET 请求，$baked 中的参数（如 cmd）始终覆盖调用方在 $query 或
     * $options['query'] 中传入的同名键，避免内置的接口标识被误改。
     *
     * @param array<string, mixed> $baked 内置且不可被覆盖的查询参数
     * @param array<string, mixed> $query 调用方查询参数
     * @param array<string, mixed> $options
     * @param list<string> $required 针对合并后查询的必填校验
     * @return array<string, mixed>|string
     */
    private function bakedGet(string $path, array $baked, array $query, array $options, array $required = []): array|string
    {
        $merged = $baked + array_merge($query, (array) ($options['query'] ?? []));
        unset($options['query']);

        return $this->requestWithQuery('GET', $path, $merged, $options, $required);
    }

    // =====================================================================
    // 文件
    // =====================================================================

    /**
     * 1. 下载文件（返回文件二进制）。
     * @param array<string, mixed> $query 必填 path：设备上文件的完整绝对路径
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function downloadFile(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/download', $query, $options, ['path']);
    }

    /**
     * 20-A. 文件上传（本地文件，multipart；成功返回纯文本）。
     * @param array<string, mixed> $form 必填 file：文件路径 / SplFileInfo / 资源句柄
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function uploadFile(array $form = [], array $options = []): array|string
    {
        return $this->requestWithMultipart('POST', '/upload', $form, $options, ['file']);
    }

    /**
     * 20-B. 文件上传（通过文件 URL，接口自行下载；task 已内置为 upload）。
     * @param array<string, mixed> $query 必填 file：文件 URL
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function uploadFileByUrl(array $query = [], array $options = []): array|string
    {
        return $this->bakedGet('/', ['task' => 'upload'], $query, $options, ['file']);
    }

    // =====================================================================
    // 剪贴板
    // =====================================================================

    /**
     * 2. 获取剪贴板内容。
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getClipboard(array $options = []): array|string
    {
        return $this->request('GET', '/clipboard', $options);
    }

    /**
     * 3. 设置剪贴板内容（cmd=2 已内置）。
     * @param array<string, mixed> $query 必填 text（含特殊字符时需 URL 编码）
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function setClipboard(array $query = [], array $options = []): array|string
    {
        return $this->bakedGet('/clipboard', ['cmd' => 2], $query, $options, ['text']);
    }

    // =====================================================================
    // S5 代理
    // =====================================================================

    /**
     * 4. 查询 S5 代理状态。
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getProxyStatus(array $options = []): array|string
    {
        return $this->request('GET', '/proxy', $options);
    }

    /**
     * 5. 设置/启动 S5 代理（cmd=2 已内置）。
     * @param array<string, mixed> $query 必填 port、usr、pwd；可选 type（1 本地 / 2 服务端域名解析）
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function setS5Proxy(array $query = [], array $options = []): array|string
    {
        return $this->bakedGet('/proxy', ['cmd' => 2], $query, $options, ['port', 'usr', 'pwd']);
    }

    /**
     * 6. 停止 S5 代理（cmd=3 已内置）。
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function stopProxy(array $options = []): array|string
    {
        return $this->bakedGet('/proxy', ['cmd' => 3], [], $options, []);
    }

    /**
     * 7. 设置 S5 域名过滤（cmd=4 已内置，POST 直传域名数组）。
     * @param list<string> $domains 域名列表，例如 ['qq.com', 'baidu.com']
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function setProxyDomainFilter(array $domains = [], array $options = []): array|string
    {
        $options['query'] = ['cmd' => 4] + (array) ($options['query'] ?? []);

        return $this->requestWithJson('POST', '/proxy', $domains, $options, []);
    }

    // =====================================================================
    // 短信 / 通话记录 / 联系人
    // =====================================================================

    /**
     * 8. 接收短信（模拟，cmd=4 已内置）。
     * @param array<string, mixed> $body 必填 address；可选 body/mbody、scaddress
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function sendSms(array $body = [], array $options = []): array|string
    {
        $options['query'] = ['cmd' => 4] + (array) ($options['query'] ?? []);

        return $this->requestWithJson('POST', '/sms', $body, $options, ['address']);
    }

    /**
     * 22. 新增通话记录。
     * @param array<string, mixed> $query 必填 number；可选 type(1呼出/2接收/3错过)、date、duration 等
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function addCallLog(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/callog', $query, $options, ['number']);
    }

    /**
     * 26. 添加联系人。
     * @param array<string, mixed> $query 必填 data：JSON 字符串，形如 [{"user":"张三","tel":"138..."}]
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function addContacts(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/addcontact', $query, $options, ['data']);
    }

    // =====================================================================
    // 证书 / 应用安装与备份 / 模块
    // =====================================================================

    /**
     * 9. 上传 Google 证书（PEM，multipart）。
     * @param array<string, mixed> $form 必填 file
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function uploadKeybox(array $form = [], array $options = []): array|string
    {
        return $this->requestWithMultipart('POST', '/uploadkeybox', $form, $options, ['file']);
    }

    /**
     * 16. 批量安装 apks/xapk 分包（ZIP，multipart；安卓 10/12(v22.9.2+)/14）。
     * @param array<string, mixed> $form 必填 file：含多个 apks/xapk 的 ZIP；可选 installer
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function installApks(array $form = [], array $options = []): array|string
    {
        return $this->requestWithMultipart('POST', '/installapks', $form, $options, ['file']);
    }

    /**
     * 11. 导出 app 信息（cmd=backup 已内置）。
     * @param array<string, mixed> $query 必填 pkg、saveto
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function exportApp(array $query = [], array $options = []): array|string
    {
        return $this->bakedGet('/backrestore', ['cmd' => 'backup'], $query, $options, ['pkg', 'saveto']);
    }

    /**
     * 12. 导入 app 信息（cmd=recovery 已内置，无需 pkg）。
     * @param array<string, mixed> $query 必填 backuppath
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function importApp(array $query = [], array $options = []): array|string
    {
        return $this->bakedGet('/backrestore', ['cmd' => 'recovery'], $query, $options, ['backuppath']);
    }

    /**
     * 25. 安装/检查/卸载面具模块（安装后需重启）。
     * @param array<string, mixed> $query 必填 cmd（check/install/uninstall）、module（magisk/gms）
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function installModule(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/modulemgr', $query, $options, ['cmd', 'module']);
    }

    // =====================================================================
    // 摄像头
    // =====================================================================

    /**
     * 13. 虚拟摄像头热启动/停止。
     * @param array<string, mixed> $query 必填 cmd（start/stop）；首次 start 需 path（rtmp 地址或本地路径）
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function camera(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/camera', $query, $options, ['cmd']);
    }

    /**
     * 30. 设置虚拟摄像头源和类型（cmd=4 已内置）。
     * @param array<string, mixed> $query type（image/video/webrtc/rtmp/camera）、path、可选 resolution（1自动/2 1080p/3 720p）
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function setCamera(array $query = [], array $options = []): array|string
    {
        return $this->bakedGet('/modifydev', ['cmd' => 4], $query, $options, []);
    }

    /**
     * 31. 设置摄像头作为虚拟摄像头源（cmd=4&type=camera&path=null 已内置；需配合「魔云互联」）。
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function setCameraPhysical(array $options = []): array|string
    {
        return $this->bakedGet('/modifydev', ['cmd' => 4, 'type' => 'camera', 'path' => 'null'], [], $options, []);
    }

    /**
     * 32. 查询当前虚拟摄像头源类型（cmd=5 已内置）。
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getCameraSource(array $options = []): array|string
    {
        return $this->bakedGet('/modifydev', ['cmd' => 5], [], $options, []);
    }

    // =====================================================================
    // 设备 / 系统
    // =====================================================================

    /**
     * 10. ADB 切换权限。
     * @param array<string, mixed> $query cmd：1 查询 / 2 开启 root / 3 关闭 root（缺省视为查询）
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function adb(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/adb', $query, $options, []);
    }

    /**
     * 14. 后台保活（仅 Android 14，镜像日期 > 20251217）。
     * @param array<string, mixed> $query 必填 cmd（1 查 / 2 增 / 3 删 / 4 更新）；cmd=2/3 需 package
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function background(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/background', $query, $options, ['cmd']);
    }

    /**
     * 15. 屏蔽/启用物理按键（仅 Android 14）。
     * @param array<string, mixed> $query value：1 开启屏蔽 / 0 关闭
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function disableKey(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/disablekey', $query, $options, []);
    }

    /**
     * 17. 版本查询。
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function queryVersion(array $options = []): array|string
    {
        return $this->request('GET', '/queryversion', $options);
    }

    /**
     * 21. 获取容器信息。
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function info(array $options = []): array|string
    {
        return $this->request('GET', '/info', $options);
    }

    /**
     * 23. 根据 IP 刷新定位。
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function refreshLocation(array $options = []): array|string
    {
        return $this->request('GET', '/task', $options);
    }

    /**
     * 24. 谷歌广告 ID（adid）。
     * @param array<string, mixed> $query 必填 cmd（1 自定义需传 adid / 2 随机）
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function googleAdId(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/adid', $query, $options, ['cmd']);
    }

    /**
     * 35. IP 定位 / 语言（cmd=11 已内置）。
     * @param array<string, mixed> $query 可选 launage（语言代码，注意 API 拼写为 launage）、ip
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function setIpLocation(array $query = [], array $options = []): array|string
    {
        return $this->bakedGet('/modifydev', ['cmd' => 11], $query, $options, []);
    }

    /**
     * 36. 设置语言和国家（cmd=13 已内置）。
     * @param array<string, mixed> $query 必填 language、country
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function setLanguageCountry(array $query = [], array $options = []): array|string
    {
        return $this->bakedGet('/modifydev', ['cmd' => 13], $query, $options, ['language', 'country']);
    }

    /**
     * 38. 更新指纹信息（cmd=7 已内置；安卓 12 与 CQR14-ALL-v1.4.0+）。
     * @param array<string, mixed> $query 必填 data：设备指纹 JSON 字符串（传原始 JSON 即可，Guzzle 会自动 URL 编码）
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function updateFingerprint(array $query = [], array $options = []): array|string
    {
        return $this->bakedGet('/modifydev', ['cmd' => 7], $query, $options, ['data']);
    }

    /**
     * 39. 设置摇一摇状态（cmd=17 已内置）。
     * @param array<string, mixed> $query 必填 shake：1 启用 / 0 不启用
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function setShake(array $query = [], array $options = []): array|string
    {
        return $this->bakedGet('/modifydev', ['cmd' => 17], $query, $options, ['shake']);
    }

    /**
     * 41. 设置运动传感器灵敏度（cmd=17 已内置，与 {@see self::setShake()} 同一 cmd）。
     * @param array<string, mixed> $query 可选 orientation、tilt、shake(0关/1持续/≥2按次)、shake_delay、scale
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function setMotionSensor(array $query = [], array $options = []): array|string
    {
        return $this->bakedGet('/modifydev', ['cmd' => 17], $query, $options, []);
    }

    /**
     * 40. 设置应用权限（cmd=18 已内置）。
     * @param array<string, mixed> $query 必填 pkg：应用包名
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function setAppPermission(array $query = [], array $options = []): array|string
    {
        return $this->bakedGet('/modifydev', ['cmd' => 18], $query, $options, ['pkg']);
    }

    // =====================================================================
    // 自动化
    // =====================================================================

    /**
     * 18. 截图（返回图片二进制）。
     * @param array<string, mixed> $query 可选 type、quality（1-100）
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function snapshot(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/snapshot', $query, $options, []);
    }

    /**
     * 37. 获取设备截图（task=snap 已内置；区别于 {@see self::snapshot()}，按分辨率级别取图，返回二进制）。
     * @param array<string, mixed> $query 必填 level：1 低 / 2 中 / 3 原始
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function deviceSnapshot(array $query = [], array $options = []): array|string
    {
        return $this->bakedGet('/', ['task' => 'snap'], $query, $options, ['level']);
    }

    /**
     * 19. 自动点击 / 触控 / 按键。
     * @param array<string, mixed> $query 必填 action（touchdown/touchup/touchmove/tap/keypress）；
     *                                    多指 id(1-10)、x、y；action=keypress 时需 code（键码）
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function autoClick(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/autoclick', $query, $options, ['action']);
    }

    // =====================================================================
    // Root 授权 / 开机启动
    // =====================================================================

    /**
     * 28. 获取后台允许 root 的 app 列表（cmd=10&action=list 已内置）。
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getRootApps(array $options = []): array|string
    {
        return $this->bakedGet('/modifydev', ['cmd' => 10, 'action' => 'list'], [], $options, []);
    }

    /**
     * 29. 指定包名允许 root（cmd=10&root=true 已内置；需先安装对应 apk）。
     * @param array<string, mixed> $query 必填 pkg
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function allowRoot(array $query = [], array $options = []): array|string
    {
        return $this->bakedGet('/modifydev', ['cmd' => 10, 'root' => 'true'], $query, $options, ['pkg']);
    }

    /**
     * 33. 获取 APP 开机启动列表（cmd=1 已内置）。
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getBootApps(array $options = []): array|string
    {
        return $this->bakedGet('/appbootstart', ['cmd' => 1], [], $options, []);
    }

    /**
     * 34. 设置开机启动 APP（cmd=2 已内置，POST 直传包名数组）。
     * @param list<string> $packages 例如 ['cn.test', 'android.ttt']
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function setBootApps(array $packages = [], array $options = []): array|string
    {
        $options['query'] = ['cmd' => 2] + (array) ($options['query'] ?? []);

        return $this->requestWithJson('POST', '/appbootstart', $packages, $options, []);
    }

    // =====================================================================
    // 投屏（WebRTC 本地播放器地址；仅拼接 URL，非 HTTP 请求）
    // =====================================================================

    /**
     * 27. 生成 WebRTC 本地播放器地址（webplayer/play.html）。
     *
     * @param string $ip            流媒体服务器主机地址
     * @param int    $webrtcTcpPort WebRTC TCP 端口（sport）
     * @param int    $webrtcUdpPort WebRTC UDP 端口（rtc_p）
     * @param string $codec         视频编码（默认 h264）
     * @param int    $quality       视频质量 0 低 / 1 高（默认 1）
     * @param string $playerPath    播放器页面相对/绝对路径
     */
    public static function webrtcPlayerUrl(
        string $ip,
        int $webrtcTcpPort,
        int $webrtcUdpPort,
        string $codec = 'h264',
        int $quality = 1,
        string $playerPath = 'webplayer/play.html',
    ): string {
        return sprintf(
            '%s?shost=%s&sport=%d&q=%d&v=%s&rtc_i=%s&rtc_p=%d',
            $playerPath,
            rawurlencode($ip),
            $webrtcTcpPort,
            $quality,
            rawurlencode($codec),
            rawurlencode($ip),
            $webrtcUdpPort,
        );
    }
}
