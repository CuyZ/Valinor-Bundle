<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use CuyZ\Valinor\Mapper\TreeMapper;
use CuyZ\Valinor\MapperBuilder;
use CuyZ\ValinorBundle\Cache\MapperCacheClearer;
use CuyZ\ValinorBundle\Cache\MapperCacheWarmer;
use CuyZ\ValinorBundle\Configurator\AllowedExceptionsConfigurator;
use CuyZ\ValinorBundle\Configurator\CacheConfigurator;
use CuyZ\ValinorBundle\Configurator\DateFormatsConfigurator;
use CuyZ\ValinorBundle\Configurator\MapperBuilderConfigurator;
use CuyZ\ValinorBundle\Console\ConsoleMappingErrorPrinter;
use CuyZ\ValinorBundle\DependencyInjection\Factory\MapperBuilderFactory;
use CuyZ\ValinorBundle\DependencyInjection\Factory\CacheFactory;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Throwable;

use function array_map;
use function in_array;

return static function (ContainerConfigurator $container, ContainerBuilder $builder): void {
    /**
     * @var array{
     *     mapper: array{
     *         date_formats_supported: array<scalar>,
     *         allowed_exceptions: array<class-string<Throwable>>,
     *     },
     *     cache: array{
     *         service: string,
     *         env_where_files_are_watched: array<string>,
     *     },
     *     console: array{
     *         mapping_errors_to_output: int,
     *     }
     * } $config
     */
    $config = $builder->getExtensionConfig('valinor')[0];

    $builder
        ->registerForAutoconfiguration(MapperBuilderConfigurator::class)
        ->addTag('valinor.mapper_builder_configurator');

    // @formatter:off
    $container->services()
        ->alias(TreeMapper::class, 'valinor.tree_mapper')

        ->set('valinor.tree_mapper', TreeMapper::class)
            ->factory([service('valinor.mapper_builder'), 'mapper'])

        ->set('valinor.mapper_builder', MapperBuilder::class)
            ->factory([
                inline_service(MapperBuilderFactory::class)->args([tagged_iterator('valinor.mapper_builder_configurator')]),
                'create'
            ])

        ->set('valinor.cache.filesystem', CacheInterface::class)
            ->factory([CacheFactory::class, 'create'])
            ->args([
                '%kernel.cache_dir%/Valinor',
                in_array(
                    $container->env(),
                    array_map('trim', $config['cache']['env_where_files_are_watched']),
                    true
                )
            ])

        ->set(null, CacheConfigurator::class)
            ->tag('valinor.mapper_builder_configurator')
            ->args([service($config['cache']['service'])])

        ->set(null, DateFormatsConfigurator::class)
            ->tag('valinor.mapper_builder_configurator')
            ->args([
                array_map('strval', $config['mapper']['date_formats_supported'])
            ])

        ->set(null, AllowedExceptionsConfigurator::class)
            ->tag('valinor.mapper_builder_configurator')
            ->args([$config['mapper']['allowed_exceptions']])

        ->set(null, MapperCacheWarmer::class)
            ->tag('kernel.cache_warmer')
            ->args([
                tagged_locator('valinor.warmup'),
                service('valinor.mapper_builder')
            ])

        ->set(null, MapperCacheClearer::class)
            ->tag('kernel.cache_clearer')
            ->args([service($config['cache']['service'])])
    ;

    $mappingErrorsToOutput = $config['console']['mapping_errors_to_output'];

    if ($mappingErrorsToOutput > 0) {
        $container->services()
            ->set(null, ConsoleMappingErrorPrinter::class)
            ->tag('kernel.event_listener')
            ->args([$mappingErrorsToOutput]);
    }
};
