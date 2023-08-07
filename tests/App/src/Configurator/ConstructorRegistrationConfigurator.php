<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\App\Configurator;

use CuyZ\Valinor\MapperBuilder;
use CuyZ\ValinorBundle\Configurator\MapperBuilderConfigurator;
use CuyZ\ValinorBundle\Tests\App\Objects\ObjectWithStaticConstructor;

final class ConstructorRegistrationConfigurator implements MapperBuilderConfigurator
{
    public function configure(MapperBuilder $builder): MapperBuilder
    {
        // PHP8.1 First-class callable syntax
        return $builder->registerConstructor([ObjectWithStaticConstructor::class, 'create']);
    }
}
