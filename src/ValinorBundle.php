<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle;

use CuyZ\ValinorBundle\DependencyInjection\CompilerPass\MapperConfigurationPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Symfony6.4 extend AbstractBundle and import Configuration and Extension here
 *
 * @api
 */
final class ValinorBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new MapperConfigurationPass());
    }
}
