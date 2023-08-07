<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\DependencyInjection;

use Exception;
use RuntimeException;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Throwable;

/** @internal */
final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('valinor');

        /** @var ArrayNodeDefinition $root */
        $root = $treeBuilder->getRootNode();
        // @formatter:off
        // @infection-ignore-all
        $root->children()
            ->arrayNode('mapper')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('date_formats_supported')
                        ->info('List of date formats supported by the mapper.')
                        ->scalarPrototype()
                            ->validate()
                                ->ifTrue(fn ($value) => trim($value) === '')
                                ->thenInvalid('Invalid date format, must be a non-empty string.')
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('allowed_exceptions')
                        ->info(
                            "List of userland exceptions classes accepted during the mapping.\n\n" .
                            "It is advised to use this feature with caution: userland exceptions may\n" .
                            "contain sensible information — for instance an SQL exception showing a\n" .
                            "part of a query should never be allowed. Therefore, only an exhaustive\n" .
                            "list of carefully chosen exceptions should be filtered."
                        )
                        ->scalarPrototype()
                            ->validate()
                                ->ifTrue(fn ($value) => !is_a($value, Throwable::class, true))
                                ->thenInvalid('Invalid exception name %`.')
                            ->end()
                            ->validate()
                                ->ifTrue(fn ($value) => in_array($value, [Throwable::class, Exception::class, RuntimeException::class], true))
                                ->thenInvalid('Invalid exception name %s, it cannot be a subclass of native PHP exception classes.')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('cache')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('service')
                        ->info('Id of the Symfony cache service used to improve the mapper performance.')
                        ->defaultValue('valinor.cache.filesystem')
                    ->end()
                    ->arrayNode('env_where_files_are_watched')
                        ->info(
                            'List of environments where the mapper should watch for file changes, preventing ' .
                            'outdated class definitions to cause invalid mapping behaviour.'
                        )
                        ->scalarPrototype()->end()
                        ->defaultValue(['dev'])
                    ->end()
                ->end()
            ->end()
            ->arrayNode('console')
                ->addDefaultsIfNotSet()
                ->children()
                    ->integerNode('mapping_errors_to_output')
                        ->info('Maximum number of detailed mapping errors to be displayed to the console. Set to 0 to disable.')
                        ->min(0)
                        ->defaultValue(10)
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
