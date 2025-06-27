<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use CuyZ\Valinor\Normalizer\Normalizer;
use CuyZ\ValinorBundle\Tests\App\Configurator\TransformerRegistrationConfigurator;
use CuyZ\ValinorBundle\Tests\App\Normalizer\NormalizerContainer;
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

    if (interface_exists(Normalizer::class)) {
        $container
            ->services()
            ->set('app.normalizer_container', NormalizerContainer::class)
                ->public()
                ->autowire()
        ;
    }

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

        ->set(TransformerRegistrationConfigurator::class)
            ->autoconfigure()

        ->set(ObjectWithWarmupAttribute::class)
            ->autoconfigure()
    ;
};
