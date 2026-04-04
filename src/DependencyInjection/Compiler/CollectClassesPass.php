<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use function method_exists;

/** @internal */
final class CollectClassesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        // Symfony6.4 remove `method_exists` check
        if (!method_exists($container, 'findTaggedResourceIds') || !$container->hasDefinition('valinor.cache.mapper_cache_warmer')) {
            return;
        }

        $classes = [];

        foreach ($container->findTaggedResourceIds('valinor.warmup') as $id => $tags) {
            $classes[] = $container->getDefinition($id)->getClass();
        }

        $container->findDefinition('valinor.cache.mapper_cache_warmer')
            ->replaceArgument(0, $classes);
    }
}
