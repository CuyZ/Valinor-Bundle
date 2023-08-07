<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests;

use CuyZ\ValinorBundle\Tests\App\AppKernel;
use PHPUnit\Event\Test\Prepared;
use PHPUnit\Event\Test\PreparedSubscriber;
use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;
use Symfony\Component\Filesystem\Filesystem;

final class ValinorPHPUnitExtension implements Extension
{
    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        $facade->registerSubscribers(new class () implements PreparedSubscriber {
            public function notify(Prepared $event): void
            {
                (new Filesystem())->remove([__DIR__ . '/../var/cache/test/' . AppKernel::testDirectory()]);
            }
        });
    }
}
