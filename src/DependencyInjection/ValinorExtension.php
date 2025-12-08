<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

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
    }
}
