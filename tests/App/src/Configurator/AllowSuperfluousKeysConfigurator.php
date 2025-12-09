<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\App\Configurator;

use CuyZ\Valinor\Mapper\Configurator\MapperBuilderConfigurator;
use CuyZ\Valinor\MapperBuilder;

final class AllowSuperfluousKeysConfigurator implements MapperBuilderConfigurator
{
    public function configureMapperBuilder(MapperBuilder $builder): MapperBuilder
    {
        return $builder->allowSuperfluousKeys();
    }
}
