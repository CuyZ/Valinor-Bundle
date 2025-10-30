<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Configurator;

use CuyZ\Valinor\Cache\Cache;
use CuyZ\Valinor\MapperBuilder;
use CuyZ\Valinor\NormalizerBuilder;

/** @internal */
final class CacheConfigurator implements MapperBuilderConfigurator, NormalizerBuilderConfigurator
{
    public function __construct(
        /** @var Cache<mixed> */
        private Cache $cache,
    ) {}

    public function configureMapperBuilder(MapperBuilder $builder): MapperBuilder
    {
        return $builder->withCache($this->cache);
    }

    public function configureNormalizerBuilder(NormalizerBuilder $builder): NormalizerBuilder
    {
        return $builder->withCache($this->cache);
    }
}
