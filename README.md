# MYT PHP SDK (Hyperf 3.1 / Swoole)

基于 `api-1.json` (OpenAPI 3.0) 生成的 PHP Composer SDK。

- 150 个接口方法（按 tag 拆分为 16 个 Service）
- 默认 HTTP 实现为 Guzzle 7
- 支持自定义 `clientFactory`（适配 Hyperf 协程 Handler）
- 支持用户自定义连接池（`ConnectionPoolInterface`）

## 安装

```bash
composer require prowendi/myt-php-sdk
```

## 快速开始

```php
<?php

declare(strict_types=1);

use Myt\PhpSdk\MytSdk;

$sdk = new MytSdk([
    'base_uri' => 'http://127.0.0.1:9511',
    'timeout' => 30,
    'connect_timeout' => 5,
]);

$list = $sdk->android()->getAndroid([
    'running' => true,
]);

$created = $sdk->android()->postAndroid([
    'name' => 'test-01',
    'imageUrl' => 'my-image:latest',
    'dns' => ['8.8.8.8'],
]);
```

## Service 列表

- `$sdk->android()` 云机操作（36）
- `$sdk->androidV2()` 云机操作V2镜像（10）
- `$sdk->backup()` 云机备份（3）
- `$sdk->auth()` 接口认证（2）
- `$sdk->info()` 基本信息（2）
- `$sdk->terminal()` 终端（5）
- `$sdk->llm()` 大模型管理（11）
- `$sdk->m50()` M50管理（7）
- `$sdk->macVlan()` macVlan网卡管理（4）
- `$sdk->mytBridge()` myt_bridge网卡管理（4）
- `$sdk->iscsi()` iSCSI磁盘管理（13）
- `$sdk->vpc()` 魔云腾VPC（24）
- `$sdk->phoneModel()` 本地机型数据管理（4）
- `$sdk->rpa()` RPA自动化（17）
- `$sdk->server()` 服务（6）
- `$sdk->user()` 用户（2）

## Hyperf 3.1 / Swoole 适配

安装后可直接通过 Hyperf 的 `ConfigProvider` 注入 `Myt\\PhpSdk\\MytSdk`（已在 `composer.json` 的 `extra.hyperf.config` 声明）。

示例配置（`config/autoload/myt_sdk.php`）：

```php
<?php

declare(strict_types=1);

return [
    'base_uri' => 'http://127.0.0.1:9511',
    'timeout' => 30,
    'connect_timeout' => 5,
    'use_hyperf_coroutine' => true,
];
```

### 1) 使用 Hyperf 协程 Handler

```php
<?php

declare(strict_types=1);

use Myt\PhpSdk\Http\HyperfCoroutineClientFactory;
use Myt\PhpSdk\MytSdk;

$sdk = new MytSdk([
    'base_uri' => 'http://127.0.0.1:9511',
    'client_factory' => static fn(array $guzzleConfig) => HyperfCoroutineClientFactory::create($guzzleConfig),
]);
```

### 2) 配置自定义连接池

SDK 的连接池接口：`Myt\PhpSdk\Contract\ConnectionPoolInterface`

```php
<?php

declare(strict_types=1);

use GuzzleHttp\ClientInterface;
use Myt\PhpSdk\Contract\ConnectionPoolInterface;

final class MyGuzzlePool implements ConnectionPoolInterface
{
    public function acquire(): ClientInterface
    {
        // 从你的池子里取一个 Guzzle Client
    }

    public function release(ClientInterface $client): void
    {
        // 放回池子
    }
}
```

注入：

```php
$sdk = new MytSdk([
    'base_uri' => 'http://127.0.0.1:9511',
    'connection_pool' => new MyGuzzlePool(),
]);
```

或者用闭包快速适配：

```php
<?php

declare(strict_types=1);

use Myt\PhpSdk\Http\CallbackConnectionPool;
use Myt\PhpSdk\MytSdk;

$pool = CallbackConnectionPool::from(
    acquire: static fn() => $container->get(MyGuzzlePool::class)->acquire(),
    release: static fn($client) => $container->get(MyGuzzlePool::class)->release($client),
);

$sdk = new MytSdk([
    'base_uri' => 'http://127.0.0.1:9511',
    'connection_pool' => $pool,
]);
```

## 上传文件接口（multipart）

上传字段可直接传文件路径、`SplFileInfo` 或资源句柄：

```php
$result = $sdk->android()->postAndroidImageImport([
    'file' => '/path/to/image.tar',
]);
```

## 错误处理

请求异常抛出 `Myt\PhpSdk\Exception\ApiException`，包含：

- `statusCode`
- `responseBody`
- `headers`
- `method`
- `uri`

## 安卓容器 API（MYT_ANDROID_API，独立客户端）

> 这是「单个安卓容器」的本机控制接口（文档：<https://dev.moyunteng.com/docs/NewMYTOS/MYT_ANDROID_API>），
> 与上面基于 `api-1.json` 的宿主机管理 SDK（`MytSdk`，形如 `http://127.0.0.1:9511`）**完全独立、互不冲突**：
> 命名空间为 `Myt\PhpSdk\Android`，调用地址为 `http://{设备IP}:{端口}`，复用同一套 HTTP/传输层基础设施。

端口规则（由 `Myt\PhpSdk\Android\AndroidPort` 计算）：

- 桥接模式：API 端口固定 `9082`
- 非桥接模式：API 端口 = `30000 + (实例位 index - 1) × 100 + 1`

### 快速开始

```php
<?php

use Myt\PhpSdk\Android\AndroidApiClient;

// 方式一：直接给 base_uri
$android = AndroidApiClient::create(['base_uri' => 'http://192.168.30.2:9082']);

// 方式二：按「设备 IP + 实例位」自动算端口（非桥接 index=3 => 端口 30201）
$android = AndroidApiClient::forContainer('192.168.30.2', index: 3, bridge: false);

$android->setClipboard(['text' => 'hello']);            // GET /clipboard?cmd=2
$png = $android->snapshot(['quality' => 80]);           // GET /snapshot（返回二进制）
$android->setS5Proxy(['port' => 8080, 'usr' => 'u', 'pwd' => 'p', 'type' => 2]);
$android->setProxyDomainFilter(['qq.com', 'baidu.com']);// POST /proxy?cmd=4（直传数组）
$android->autoClick(['action' => 'tap', 'id' => 1, 'x' => 100, 'y' => 100]);
$android->uploadFile(['file' => '/path/to/app.apk']);   // multipart 上传
```

涵盖文档全部 41 个接口：剪贴板、S5 代理、短信/通话/联系人、Google 证书、应用安装(apks/xapk)/备份、面具模块、虚拟摄像头（设置源/查询源/物理摄像头）、ADB 权限、后台保活、屏蔽按键、截图(`snapshot`/`deviceSnapshot`)、自动点击、root 授权、开机启动、语言/IP 定位、更新指纹、摇一摇/运动传感器、应用权限、文件上传/下载等。单一用途接口已内置 `cmd`（如 `setClipboard` 内置 `cmd=2`、`stopProxy` 内置 `cmd=3`），且内置参数不可被调用方覆盖；多形态接口（`adb`/`camera`/`background`/`googleAdId`/`installModule`）的 `cmd` 由调用方传入。

> 返回约定：成功多为信封 JSON `{"code":200,"msg":"ok","data":{...}}`；截图/下载返回二进制（字符串）。
> **业务失败以 HTTP 200 + `code=201/202`（`error`/`reason` 字段）返回，不会抛 `ApiException`，需自行检查 `$res['code']`**；仅当 HTTP ≥ 400 才抛 `ApiException`。

## 生成说明

- 源文档：`api-1.json`
- Service 代码由脚本生成：`tools/generate_services.py`
- 生成产物：`src/Service/*Service.php`
