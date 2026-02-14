# MYT PHP SDK (Hyperf 3.1 / Swoole)

基于 `api-1.json` (OpenAPI 3.0) 生成的 PHP Composer SDK。

- 87 个接口方法（按 tag 拆分为 12 个 Service）
- 默认 HTTP 实现为 Guzzle 7
- 支持自定义 `clientFactory`（适配 Hyperf 协程 Handler）
- 支持用户自定义连接池（`ConnectionPoolInterface`）

## 安装

```bash
composer require myt/php-sdk
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

- `$sdk->android()` 云机操作（30）
- `$sdk->androidV2()` 云机操作V2镜像（3）
- `$sdk->backup()` 云机备份（3）
- `$sdk->auth()` 接口认证（2）
- `$sdk->info()` 基本信息（2）
- `$sdk->terminal()` 终端（5）
- `$sdk->llm()` 大模型管理（10）
- `$sdk->macVlan()` macVlan网卡管理（4）
- `$sdk->mytBridge()` myt_bridge网卡管理（4）
- `$sdk->vpc()` 魔云腾VPC（14）
- `$sdk->phoneModel()` 本地机型数据管理（4）
- `$sdk->server()` 服务（6）

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

## 生成说明

- 源文档：`api-1.json`
- Service 代码由脚本生成：`tools/generate_services.py`
- 生成产物：`src/Service/*Service.php`
