<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\Integration\Mapper;

use CuyZ\ValinorBundle\Tests\App\Cache\CacheSpy;
use CuyZ\ValinorBundle\Tests\App\Objects\ObjectWithWarmupAttribute;
use CuyZ\ValinorBundle\Tests\App\Objects\ObjectWithWarmupTag;
use CuyZ\ValinorBundle\Tests\Integration\IntegrationTestCase;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerAggregate;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

final class WarmupTest extends IntegrationTestCase
{
    public function test_object_with_warmup_tag_are_warmed_up(): void
    {
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
        $cacheWarmer = $this->cacheWarmer();
        $cacheDir = $this->cacheDir();

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

    private function cacheWarmer(): CacheWarmerAggregate
    {
        return self::getContainer()->get('cache_warmer');
    }

    private function cacheDir(): string
    {
        return self::getContainer()->getParameter('kernel.cache_dir');
    }
}
