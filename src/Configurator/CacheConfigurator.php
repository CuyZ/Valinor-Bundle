<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Configurator;

use CuyZ\Valinor\MapperBuilder;
use Psr\SimpleCache\CacheInterface;

/** @internal */
final class CacheConfigurator implements MapperBuilderConfigurator
{
    public function __construct(
        private CacheInterface $cache,
    ) {}

    public function configure(MapperBuilder $builder): MapperBuilder
    {
        return $builder->withCache($this->cache);
    }
}
