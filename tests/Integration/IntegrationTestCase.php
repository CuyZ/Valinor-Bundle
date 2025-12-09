<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\Integration;

use CuyZ\ValinorBundle\Tests\App\AppKernel;
use CuyZ\ValinorBundle\Tests\App\Mapper\MapperContainer;
use CuyZ\ValinorBundle\Tests\App\Normalizer\NormalizerContainer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class IntegrationTestCase extends WebTestCase
{
    /** @var array<callable(ContainerConfigurator): void> */
    private static array $configurators = [];

    protected function setUp(): void
    {
        parent::setUp();

        self::$configurators = [];

        (new Filesystem())->remove([__DIR__ . '/../../var/cache/test/' . AppKernel::testDirectory()]);
    }

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

    protected function normalizerContainer(): NormalizerContainer
    {
        return self::getContainer()->get('app.normalizer_container');
    }
}
