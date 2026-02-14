<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Hyperf\Factory;

use InvalidArgumentException;
use Myt\PhpSdk\Http\HyperfCoroutineClientFactory;
use Myt\PhpSdk\MytSdk;
use Psr\Container\ContainerInterface;

final class MytSdkFactory
{
    public function __invoke(ContainerInterface $container): MytSdk
    {
        $config = [];
        $configInterface = '\\Hyperf\\Contract\\ConfigInterface';

        if (interface_exists($configInterface) && $container->has($configInterface)) {
            $configService = $container->get($configInterface);
            $config = (array) $configService->get('myt_sdk', []);
        }

        if (($config['use_hyperf_coroutine'] ?? true) === true && !isset($config['client_factory'])) {
            $config['client_factory'] = static fn(array $guzzleConfig) => HyperfCoroutineClientFactory::create($guzzleConfig);
        }

        if (!isset($config['base_uri']) && !isset($config['baseUri'])) {
            throw new InvalidArgumentException('Missing Hyperf config: myt_sdk.base_uri');
        }

        return new MytSdk($config);
    }
}
