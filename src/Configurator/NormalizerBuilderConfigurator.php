<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Configurator;

use CuyZ\Valinor\NormalizerBuilder;

/**
 * This interface can be implemented by services that will configure the
 * normalizer builder.
 *
 * If the service is autoconfigured, it will automatically be used, otherwise it
 * needs to be tagged with the tag `valinor.normalizer_builder_configurator`.
 *
 * ```php
 * use CuyZ\Valinor\NormalizerBuilder;
 * use CuyZ\ValinorBundle\Configurator\NormalizerBuilderConfigurator;
 *
 * final class TransformerRegistrationConfigurator implements NormalizerBuilderConfigurator
 * {
 *     public function configureNormalizerBuilder(NormalizerBuilder $builder): NormalizerBuilder
 *     {
 *         return $builder->registerTransformer(
 *             fn (string $value): string => strtoupper($value),
 *         );
 *     }
 * }
 * ```
 *
 * @api
 */
interface NormalizerBuilderConfigurator
{
    public function configureNormalizerBuilder(NormalizerBuilder $builder): NormalizerBuilder;
}
