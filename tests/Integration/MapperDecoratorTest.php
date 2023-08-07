<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\Integration;

use CuyZ\Valinor\Mapper\TreeMapper;
use CuyZ\Valinor\Mapper\TypeTreeMapper;
use CuyZ\ValinorBundle\Tests\App\Mapper\TreeMapperDecoratorFromAttribute;
use CuyZ\ValinorBundle\Tests\App\Mapper\TreeMapperDecoratorFromConfig;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;

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

        // @phpstan-ignore-next-line Symfony6.4 remove
        if (Kernel::MAJOR_VERSION === 5) {
            self::assertInstanceOf(TreeMapperDecoratorFromConfig::class, $this->mapperContainer()->defaultMapper);
            self::assertInstanceOf(TypeTreeMapper::class, $this->mapperContainer()->defaultMapper->inner);
            return;
        }

        self::assertInstanceOf(TreeMapperDecoratorFromAttribute::class, $this->mapperContainer()->defaultMapper);
        self::assertInstanceOf(TreeMapperDecoratorFromConfig::class, $this->mapperContainer()->defaultMapper->inner);
        self::assertInstanceOf(TypeTreeMapper::class, $this->mapperContainer()->defaultMapper->inner->inner);
    }
}
