<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Cache;

use CuyZ\Valinor\MapperBuilder;
use CuyZ\Valinor\NormalizerBuilder;
use Symfony\Component\HttpKernel\CacheClearer\CacheClearerInterface;

/** @internal */
final class CacheClearer implements CacheClearerInterface
{
    public function __construct(
        /** @var iterable<MapperBuilder> */
        private iterable $mapperBuilders,
        /** @var iterable<NormalizerBuilder> */
        private iterable $normalizerBuilders,
    ) {}

    public function clear(string $cacheDir): void
    {
        foreach ($this->mapperBuilders as $mapperBuilder) {
            $mapperBuilder->clearCache();
        }

        foreach ($this->normalizerBuilders as $normalizerBuilder) {
            $normalizerBuilder->clearCache();
        }
    }
}
