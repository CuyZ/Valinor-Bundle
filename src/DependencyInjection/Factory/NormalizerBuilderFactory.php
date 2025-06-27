<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\DependencyInjection\Factory;

use CuyZ\Valinor\NormalizerBuilder;
use CuyZ\ValinorBundle\Configurator\NormalizerBuilderConfigurator;

/** @internal */
final class NormalizerBuilderFactory
{
    public function __construct(
        /** @var iterable<NormalizerBuilderConfigurator> */
        private iterable $globalConfigurators,
    ) {}

    public function create(): NormalizerBuilder
    {
        $builder = new NormalizerBuilder();

        foreach ($this->globalConfigurators as $configurator) {
            $builder = $configurator->configureNormalizerBuilder($builder);
        }

        return $builder;
    }
}
