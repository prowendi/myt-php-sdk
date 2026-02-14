<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Contract;

use GuzzleHttp\ClientInterface;

interface ConnectionPoolInterface
{
    public function acquire(): ClientInterface;

    public function release(ClientInterface $client): void;
}
