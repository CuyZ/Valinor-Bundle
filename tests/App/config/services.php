<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Psr\Log\NullLogger;

return static function (ContainerConfigurator $container): void {
    $container->extension('framework', [
        'test' => true,
        'annotations' => ['enabled' => false],
    ]);

    $container
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->set('logger', NullLogger::class)
        ->load('CuyZ\\ValinorBundle\\Tests\\App\\', '../src/');
};
