<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use CuyZ\Valinor\Mapper\TreeMapper;
use CuyZ\ValinorBundle\Tests\App\Configurator\ConstructorRegistrationConfigurator;
use CuyZ\ValinorBundle\Tests\App\Console\FailingMappingCommand;
use CuyZ\ValinorBundle\Tests\App\Mapper\MapperContainer;
use CuyZ\ValinorBundle\Tests\App\Mapper\TreeMapperDecoratorFromAttribute;
use CuyZ\ValinorBundle\Tests\App\Objects\ObjectWithWarmupAttribute;
use Psr\Log\NullLogger;

return static function (ContainerConfigurator $container): void {
    $container->extension('framework', [
        'test' => true,
        'annotations' => ['enabled' => false],
    ]);

    $container
        ->services()

        ->set('logger', NullLogger::class)

        ->set('app.mapper_container', MapperContainer::class)
            ->public()
            ->autowire()

        ->set(TreeMapperDecoratorFromAttribute::class, TreeMapperDecoratorFromAttribute::class)
            ->autoconfigure()
            ->autowire()

        ->set(FailingMappingCommand::class)
            ->args([service(TreeMapper::class)])
            ->autoconfigure()

        ->set(ConstructorRegistrationConfigurator::class)
            ->autoconfigure()

        ->set(ObjectWithWarmupAttribute::class)
            ->autoconfigure()
    ;
};
