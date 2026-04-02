<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\DependencyInjection;

use CuyZ\ValinorBundle\Cache\WarmupForMapper;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

use function method_exists;

/** @internal */
final class ValinorExtension extends ConfigurableExtension
{
    /**
     * @param array<mixed> $mergedConfig
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        /** @var array<string, mixed> $mergedConfig */
        $container->prependExtensionConfig('valinor', $mergedConfig);
        $loader = new PhpFileLoader($container, new FileLocator(dirname(__DIR__) . '/Resources/config'));
        $loader->load('services.php');

        $container->registerAttributeForAutoconfiguration(WarmupForMapper::class, static function (ChildDefinition $definition) {
            if (method_exists($definition, 'addResourceTag')) {
                $definition->addResourceTag('valinor.warmup');
            }
        });
    }
}
