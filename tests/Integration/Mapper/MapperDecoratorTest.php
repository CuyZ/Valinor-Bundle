<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\Integration\Mapper;

use CuyZ\Valinor\Mapper\TreeMapper;
use CuyZ\Valinor\Mapper\TypeTreeMapper;
use CuyZ\ValinorBundle\Tests\App\Mapper\TreeMapperDecoratorFromAttribute;
use CuyZ\ValinorBundle\Tests\App\Mapper\TreeMapperDecoratorFromConfig;
use CuyZ\ValinorBundle\Tests\Integration\IntegrationTestCase;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

final class MapperDecoratorTest extends IntegrationTestCase
{
    public function test_tree_mapper_is_decorated_by_both_config_and_attribute(): void
    {
        $this->configureContainer(function (ContainerConfigurator $container) {
            $container->services()
                ->set(TreeMapper::class, TreeMapperDecoratorFromConfig::class)
                ->decorate('valinor.tree_mapper')
                ->args([service('.inner')]);
        });

        self::assertInstanceOf(TreeMapperDecoratorFromAttribute::class, $this->mapperContainer()->defaultMapper);
        self::assertInstanceOf(TreeMapperDecoratorFromConfig::class, $this->mapperContainer()->defaultMapper->inner);
        self::assertInstanceOf(TypeTreeMapper::class, $this->mapperContainer()->defaultMapper->inner->inner);
    }
}
