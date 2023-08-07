<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\Integration;

use Composer\InstalledVersions;
use CuyZ\ValinorBundle\Tests\App\Cache\CacheSpy;
use CuyZ\ValinorBundle\Tests\App\Objects\ObjectWithWarmupAttribute;
use CuyZ\ValinorBundle\Tests\App\Objects\ObjectWithWarmupTag;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\CacheClearer\CacheClearerInterface;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerAggregate;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

final class WarmupTest extends IntegrationTestCase
{
    public function test_object_with_warmup_tag_are_warmed_up(): void
    {
        // @phpstan-ignore-next-line
        if (version_compare(InstalledVersions::getVersion('cuyz/valinor'), '1.5.0', '<=')) {
            // @see https://github.com/CuyZ/Valinor/commit/78240837e70a4a5f987e300de20478287e17224b
            self::markTestSkipped('A bug prevents this test from passing on Valinor 1.5.0 and below');
        }

        $this->configureContainer(function (ContainerConfigurator $container) {
            $container->services()
                ->set('app.cache.spy', CacheSpy::class)
                ->args([service('valinor.cache.filesystem')])
                ->set('object_with_warmup_tag', ObjectWithWarmupTag::class)
                ->tag('valinor.warmup');

            $container->extension('valinor', [
                'cache' => [
                    'service' => 'app.cache.spy',
                ],
            ]);
        });

        $cacheSpy = $this->cacheSpy();
        $cacheClearer = $this->cacheClearer();
        $cacheWarmer = $this->cacheWarmer();
        $cacheDir = $this->cacheDir();

        $cacheClearer->clear($cacheDir);

        self::assertTrue($cacheSpy->wasCleared());

        $cacheWarmer->enableOptionalWarmers();
        $cacheWarmer->warmUp($cacheDir);

        self::assertTrue($cacheSpy->hasCachedClassDefinition(ObjectWithWarmupAttribute::class));
        self::assertTrue($cacheSpy->hasCachedClassDefinition(ObjectWithWarmupTag::class));
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

    private function cacheWarmer(): CacheWarmerAggregate
    {
        return self::getContainer()->get('cache_warmer');
    }

    private function cacheDir(): string
    {
        return self::getContainer()->getParameter('kernel.cache_dir');
    }
}
