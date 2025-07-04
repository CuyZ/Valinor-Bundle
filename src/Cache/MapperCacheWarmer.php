<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Cache;

use CuyZ\Valinor\MapperBuilder;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Contracts\Service\ServiceProviderInterface;

/** @internal */
final class MapperCacheWarmer implements CacheWarmerInterface
{
    public function __construct(
        private ServiceProviderInterface $classesToWarmup,
        private MapperBuilder $mapperBuilder
    ) {}

    public function warmUp(string $cacheDir, ?string $buildDir = null): array
    {
        $this->mapperBuilder->warmupCacheFor(...$this->classesToWarmup->getProvidedServices());

        return [];
    }

    public function isOptional(): bool
    {
        return true;
    }
}
