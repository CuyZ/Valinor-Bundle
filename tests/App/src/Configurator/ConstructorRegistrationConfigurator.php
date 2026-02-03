<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\App\Configurator;

use CuyZ\Valinor\Mapper\Configurator\MapperBuilderConfigurator;
use CuyZ\Valinor\MapperBuilder;
use CuyZ\ValinorBundle\Tests\App\Objects\ObjectWithStaticConstructor;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('valinor.mapper_builder_configurator.default')]
final class ConstructorRegistrationConfigurator implements MapperBuilderConfigurator
{
    public function configureMapperBuilder(MapperBuilder $builder): MapperBuilder
    {
        return $builder->registerConstructor(ObjectWithStaticConstructor::create(...));
    }
}
