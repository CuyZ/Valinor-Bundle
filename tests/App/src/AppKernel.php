<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;

final class AppKernel extends Kernel
{
    use MicroKernelTrait {
        configureContainer as configureContainerTrait; // Symfony6.4 remove
    }

    /** @var array<callable(ContainerConfigurator): void> */
    public array $configurators = [];

    // @phpstan-ignore-next-line
    private function configureContainer(ContainerConfigurator $container, LoaderInterface $loader, ContainerBuilder $builder): void
    {
        $this->configureContainerTrait($container, $loader, $builder);

        foreach ($this->configurators as $configurator) {
            $configurator($container);
        }
    }

    public function getProjectDir(): string
    {
        return __DIR__ . '/..';
    }

    public function getCacheDir(): string
    {
        return $this->getProjectDir() . "/../../var/cache/$this->environment/" . self::testDirectory();
    }

    /**
     * Environment variable `TEST_TOKEN` is set by Infection, it is used to
     * avoid cache collisions between different test runs.
     */
    public static function testDirectory(): string
    {
        return ($token = getenv('TEST_TOKEN')) !== false
            ? "token-$token"
            : 'default';
    }
}
