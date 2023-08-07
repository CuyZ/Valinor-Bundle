<?php

namespace CuyZ\ValinorBundle\Tests\App\Configurator;

use Attribute;
use CuyZ\Valinor\MapperBuilder;
use CuyZ\ValinorBundle\Configurator\Attributes\MapperBuilderConfiguratorAttribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final class DateConfiguratorAttribute implements MapperBuilderConfiguratorAttribute
{
    public function configure(MapperBuilder $builder): MapperBuilder
    {
        return $builder
            ->supportDateFormats('Y;m;d');
    }
}
