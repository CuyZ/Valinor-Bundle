<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\DependencyInjection\Factory;

use CuyZ\Valinor\MapperBuilder;
use CuyZ\ValinorBundle\Configurator\MapperBuilderConfigurator;

/** @internal */
final class MapperBuilderFactory
{
    public function __construct(
        /** @var iterable<MapperBuilderConfigurator> */
        private iterable $globalConfigurators
    ) {}

    public function create(MapperBuilderConfigurator ...$configurators): MapperBuilder
    {
        $builder = new MapperBuilder();

        foreach ([...$this->globalConfigurators, ...$configurators] as $configurator) {
            $builder = $configurator->configure($builder);
        }

        return $builder;
    }
}
