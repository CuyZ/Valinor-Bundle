<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use CuyZ\Valinor\Cache\Cache;
use CuyZ\Valinor\Mapper\TreeMapper;
use CuyZ\Valinor\MapperBuilder;
use CuyZ\Valinor\Normalizer\ArrayNormalizer;
use CuyZ\Valinor\Normalizer\Format;
use CuyZ\Valinor\Normalizer\JsonNormalizer;
use CuyZ\Valinor\NormalizerBuilder;
use CuyZ\ValinorBundle\Cache\CacheClearer;
use CuyZ\ValinorBundle\Cache\MapperCacheWarmer;
use CuyZ\ValinorBundle\Configurator\AllowedExceptionsConfigurator;
use CuyZ\ValinorBundle\Configurator\CacheConfigurator;
use CuyZ\ValinorBundle\Configurator\DateFormatsConfigurator;
use CuyZ\ValinorBundle\Configurator\MapperBuilderConfigurator;
use CuyZ\ValinorBundle\Configurator\NormalizerBuilderConfigurator;
use CuyZ\ValinorBundle\Console\ConsoleMappingErrorPrinter;
use CuyZ\ValinorBundle\DependencyInjection\Factory\CacheFactory;
use CuyZ\ValinorBundle\DependencyInjection\Factory\MapperBuilderFactory;
use CuyZ\ValinorBundle\DependencyInjection\Factory\NormalizerBuilderFactory;
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

    $builder
        ->registerForAutoconfiguration(NormalizerBuilderConfigurator::class)
        ->addTag('valinor.normalizer_builder_configurator');

    $builder
        ->registerForAutoconfiguration(MapperBuilder::class)
        ->addTag('valinor.mapper_builder');

    $builder
        ->registerForAutoconfiguration(NormalizerBuilder::class)
        ->addTag('valinor.normalizer_builder');

    // @formatter:off
    $container->services()
        ->alias(MapperBuilder::class, 'valinor.mapper_builder')
        ->alias(TreeMapper::class, 'valinor.tree_mapper')

        ->alias(NormalizerBuilder::class, 'valinor.normalizer_builder')
        ->alias(ArrayNormalizer::class, 'valinor.normalizer.array')
        ->alias(JsonNormalizer::class, 'valinor.normalizer.json')

        ->set('valinor.mapper_builder', MapperBuilder::class)
            ->autoconfigure()
            ->factory([
                inline_service(MapperBuilderFactory::class)
                    ->args([tagged_iterator('valinor.mapper_builder_configurator')]),
                'create'
            ])

        ->set('valinor.tree_mapper', TreeMapper::class)
            ->factory([service('valinor.mapper_builder'), 'mapper'])

        ->set('valinor.normalizer_builder', NormalizerBuilder::class)
            ->autoconfigure()
            ->factory([
                inline_service(NormalizerBuilderFactory::class)
                    ->args([tagged_iterator('valinor.normalizer_builder_configurator')]),
                'create'
            ])

        ->set('valinor.normalizer.array', ArrayNormalizer::class)
            ->factory([service('valinor.normalizer_builder'), 'normalizer'])
            ->args([
                inline_service(Format::class)->factory([Format::class, 'array'])
            ])

        ->set('valinor.normalizer.json', JsonNormalizer::class)
            ->factory([service('valinor.normalizer_builder'), 'normalizer'])
            ->args([
                inline_service(Format::class)->factory([Format::class, 'json'])
            ])

        ->set('valinor.cache.filesystem', Cache::class)
            ->factory([CacheFactory::class, 'create'])
            ->args([
                '$cacheDir' => '%kernel.build_dir%/Valinor',
                '$watchFiles' => in_array(
                    $builder->getParameter('kernel.environment'),
                    array_map('trim', $config['cache']['env_where_files_are_watched']),
                    true
                )
            ])

        ->set(null, CacheConfigurator::class)
            ->tag('valinor.mapper_builder_configurator')
            ->tag('valinor.normalizer_builder_configurator')
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

        ->set(null, CacheClearer::class)
            ->tag('kernel.cache_clearer')
            ->args([
                tagged_iterator('valinor.mapper_builder'),
                tagged_iterator('valinor.normalizer_builder'),
            ])
    ;

    $mappingErrorsToOutput = $config['console']['mapping_errors_to_output'];

    if ($mappingErrorsToOutput > 0) {
        $container->services()
            ->set(null, ConsoleMappingErrorPrinter::class)
            ->tag('kernel.event_listener')
            ->args([$mappingErrorsToOutput]);
    }
};
