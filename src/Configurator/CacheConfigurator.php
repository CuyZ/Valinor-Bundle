<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Configurator;

use CuyZ\Valinor\Cache\Cache;
use CuyZ\Valinor\MapperBuilder;

/** @internal */
final class CacheConfigurator implements MapperBuilderConfigurator
{
    public function __construct(
        /** @var Cache<mixed> */
        private Cache $cache,
    ) {}

    public function configureMapperBuilder(MapperBuilder $builder): MapperBuilder
    {
        return $builder->withCache($this->cache);
    }
}
