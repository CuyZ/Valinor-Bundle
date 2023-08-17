<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\Integration;

use CuyZ\ValinorBundle\Tests\App\AppKernel;
use CuyZ\ValinorBundle\Tests\App\Mapper\MapperContainer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class IntegrationTestCase extends KernelTestCase
{
    /** @var array<callable(ContainerConfigurator): void> */
    private static array $configurators = [];

    protected static function getKernelClass(): string
    {
        return AppKernel::class;
    }

    /**
     * @param callable(ContainerConfigurator): void ...$configurators
     */
    protected function configureContainer(callable ...$configurators): void
    {
        self::$configurators = $configurators;
    }

    /**
     * @param array<mixed> $options
     */
    protected static function createKernel(array $options = []): KernelInterface
    {
        /** @var AppKernel $kernel */
        $kernel = parent::createKernel($options);
        $kernel->configurators = self::$configurators;

        return $kernel;
    }

    protected function mapperContainer(): MapperContainer
    {
        return self::getContainer()->get('app.mapper_container');
    }
}
