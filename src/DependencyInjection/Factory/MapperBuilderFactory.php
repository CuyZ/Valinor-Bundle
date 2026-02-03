<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\DependencyInjection\Factory;

use CuyZ\Valinor\Mapper\Configurator\MapperBuilderConfigurator;
use CuyZ\Valinor\MapperBuilder;
use CuyZ\ValinorBundle\Configurator\MapperBuilderConfigurator as DeprecatedMapperBuilderConfigurator;

use function array_unique;
use function implode;
use function iterator_to_array;

/** @internal */
final class MapperBuilderFactory
{
    public function __construct(
        /** @var iterable<object> */
        private iterable $deprecatedDefaultConfigurators,
        /** @var iterable<object> */
        private iterable $defaultConfigurators,
    ) {}

    public function create(): MapperBuilder
    {
        $builder = new MapperBuilder();

        $deprecatedDefaultConfigurators = iterator_to_array($this->deprecatedDefaultConfigurators);

        if ($deprecatedDefaultConfigurators !== []) {
            $classes = array_unique(array_map(fn (object $configurator) => $configurator::class, $deprecatedDefaultConfigurators));

            trigger_error(
                'In order to be automatically used, mapper builder configurator(s) need to extend `' . MapperBuilderConfigurator::class .
                '` and be tagged with `valinor.mapper_builder_configurator.default`. To keep being automatically used in ' .
                '`cuyz/valinor-bundle >= 3.0`, the following configurator(s) need to be adapted: `' . implode('`, `', $classes) . '`.',
                E_USER_DEPRECATED
            );
        }

        foreach ([...$deprecatedDefaultConfigurators, ...$this->defaultConfigurators] as $configurator) {
            if (! $configurator instanceof DeprecatedMapperBuilderConfigurator && ! $configurator instanceof MapperBuilderConfigurator) {
                continue;
            }

            $builder = $configurator->configureMapperBuilder($builder);
        }

        return $builder;
    }
}
