<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\DependencyInjection\Factory;

use CuyZ\Valinor\Cache\FileSystemCache;
use CuyZ\Valinor\Cache\FileWatchingCache;
use Psr\SimpleCache\CacheInterface;

/** @internal */
final class CacheFactory
{
    public static function create(string $cacheDir, bool $watchFiles): CacheInterface
    {
        $cache = new FileSystemCache($cacheDir);

        if ($watchFiles) {
            $cache = new FileWatchingCache($cache);
        }

        return $cache;
    }
}
