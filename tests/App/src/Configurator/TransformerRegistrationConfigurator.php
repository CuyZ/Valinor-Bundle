<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\App\Configurator;

use CuyZ\Valinor\NormalizerBuilder;
use CuyZ\ValinorBundle\Configurator\NormalizerBuilderConfigurator;

final class TransformerRegistrationConfigurator implements NormalizerBuilderConfigurator
{
    public function configureNormalizerBuilder(NormalizerBuilder $builder): NormalizerBuilder
    {
        return $builder->registerTransformer(
            fn (float $value): float => $value + 0.42,
        );
    }
}
