<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Hyperf;

use Myt\PhpSdk\Hyperf\Factory\MytSdkFactory;
use Myt\PhpSdk\MytSdk;

final class ConfigProvider
{
    /**
     * @return array<string, mixed>
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                MytSdk::class => MytSdkFactory::class,
            ],
        ];
    }
}
