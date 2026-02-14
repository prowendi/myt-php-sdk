<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Http;

use Closure;
use GuzzleHttp\ClientInterface;
use Myt\PhpSdk\Contract\ConnectionPoolInterface;
use RuntimeException;

final class CallbackConnectionPool implements ConnectionPoolInterface
{
    /**
     * @param callable():ClientInterface $acquire
     * @param callable(ClientInterface):void|null $release
     */
    public function __construct(
        private readonly Closure $acquire,
        private readonly ?Closure $release = null,
    ) {
    }

    public static function from(callable $acquire, ?callable $release = null): self
    {
        return new self(
            Closure::fromCallable($acquire),
            $release === null ? null : Closure::fromCallable($release),
        );
    }

    public function acquire(): ClientInterface
    {
        $client = ($this->acquire)();
        if (!$client instanceof ClientInterface) {
            throw new RuntimeException('ConnectionPool acquire callback must return GuzzleHttp\\ClientInterface.');
        }

        return $client;
    }

    public function release(ClientInterface $client): void
    {
        if ($this->release !== null) {
            ($this->release)($client);
        }
    }
}
