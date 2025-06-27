<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\DependencyInjection\Factory;

use CuyZ\Valinor\Cache\Cache;
use CuyZ\Valinor\Cache\FileSystemCache;
use CuyZ\Valinor\Cache\FileWatchingCache;

/** @internal */
final class CacheFactory
{
    /**
     * @return Cache<mixed>
     */
    public static function create(string $cacheDir, bool $watchFiles): Cache
    {
        $cache = new FileSystemCache($cacheDir);

        if ($watchFiles) {
            $cache = new FileWatchingCache($cache);
        }

        return $cache;
    }
}
