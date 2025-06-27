<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\Integration\Cache;

use CuyZ\ValinorBundle\Tests\App\Cache\CacheSpy;
use CuyZ\ValinorBundle\Tests\Integration\IntegrationTestCase;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use Symfony\Component\HttpKernel\CacheClearer\CacheClearerInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

final class CacheClearerTest extends IntegrationTestCase
{
    public function test_cache_is_cleared_by_cache_clearer(): void
    {
        $this->configureContainer(function (ContainerConfigurator $container) {
            $container->services()
                ->set('app.cache.spy', CacheSpy::class)
                ->args([service('valinor.cache.filesystem')]);

            $container->extension('valinor', [
                'cache' => [
                    'service' => 'app.cache.spy',
                ],
            ]);
        });

        $cacheSpy = $this->cacheSpy();
        $cacheClearer = $this->cacheClearer();
        $cacheDir = $this->cacheDir();

        $cacheClearer->clear($cacheDir);

        self::assertTrue($cacheSpy->wasCleared());
    }

    private function cacheSpy(): CacheSpy
    {
        /** @var CacheSpy */
        return self::getContainer()->get('app.cache.spy');
    }

    private function cacheClearer(): CacheClearerInterface
    {
        return self::getContainer()->get('cache_clearer');
    }

    private function cacheDir(): string
    {
        return self::getContainer()->getParameter('kernel.cache_dir');
    }
}
