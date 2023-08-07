<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Cache;

use Psr\SimpleCache\CacheInterface;
use Symfony\Component\HttpKernel\CacheClearer\CacheClearerInterface;

/** @internal */
final class MapperCacheClearer implements CacheClearerInterface
{
    public function __construct(
        private CacheInterface $cache
    ) {}

    public function clear(string $cacheDir): void
    {
        $this->cache->clear();
    }
}
