<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\DependencyInjection\Factory;

use CuyZ\Valinor\Normalizer\Configurator\NormalizerBuilderConfigurator;
use CuyZ\Valinor\NormalizerBuilder;
use CuyZ\ValinorBundle\Configurator\NormalizerBuilderConfigurator as DeprecatedNormalizerBuilderConfigurator;

/** @internal */
final class NormalizerBuilderFactory
{
    public function __construct(
        /** @var iterable<object> */
        private iterable $deprecatedDefaultConfigurators,
        /** @var iterable<object> */
        private iterable $defaultConfigurators,
    ) {}

    public function create(): NormalizerBuilder
    {
        $builder = new NormalizerBuilder();

        $deprecatedDefaultConfigurators = iterator_to_array($this->deprecatedDefaultConfigurators);

        if ($deprecatedDefaultConfigurators !== []) {
            $classes = array_unique(array_map(fn (object $configurator) => $configurator::class, $deprecatedDefaultConfigurators));

            trigger_error(
                'In order to be automatically used, normalizer builder configurator(s) need to extend `' . NormalizerBuilderConfigurator::class .
                '` and be tagged with `valinor.normalizer_builder_configurator.default`. To keep being automatically used in ' .
                '`cuyz/valinor-bundle >= 3.0`, the following configurator(s) need to be adapted: `' . implode('`, `', $classes) . '`.',
                E_USER_DEPRECATED,
            );
        }

        foreach ([...$deprecatedDefaultConfigurators, ...$this->defaultConfigurators] as $configurator) {
            if (! $configurator instanceof DeprecatedNormalizerBuilderConfigurator && ! $configurator instanceof NormalizerBuilderConfigurator) {
                continue;
            }

            $builder = $configurator->configureNormalizerBuilder($builder);
        }

        return $builder;
    }
}
