<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\App\Configurator;

use CuyZ\Valinor\MapperBuilder;
use CuyZ\ValinorBundle\Configurator\MapperBuilderConfigurator;
use CuyZ\ValinorBundle\Tests\App\Objects\ObjectWithStaticConstructor;

final class ConstructorRegistrationConfigurator implements MapperBuilderConfigurator
{
    public function configureMapperBuilder(MapperBuilder $builder): MapperBuilder
    {
        return $builder->registerConstructor(ObjectWithStaticConstructor::create(...));
    }
}
