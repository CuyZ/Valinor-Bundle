<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\App\Configurator;

use CuyZ\Valinor\Normalizer\Configurator\NormalizerBuilderConfigurator;
use CuyZ\Valinor\NormalizerBuilder;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('valinor.normalizer_builder_configurator.default')]
final class TransformerRegistrationConfigurator implements NormalizerBuilderConfigurator
{
    public function configureNormalizerBuilder(NormalizerBuilder $builder): NormalizerBuilder
    {
        return $builder->registerTransformer(
            fn (float $value): float => $value + 0.42,
        );
    }
}
