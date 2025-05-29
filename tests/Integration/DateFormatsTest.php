<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\Integration;

use DateTimeInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

final class DateFormatsTest extends IntegrationTestCase
{
    public function test_date_format_registered_in_config_is_respected(): void
    {
        $this->configureContainer(function (ContainerConfigurator $container) {
            $container->extension('valinor', [
                'mapper' => [
                    'date_formats_supported' => ['Y-m-d', 'Y-m-d H:i:s'],
                ],
            ]);
        });

        $date = $this->mapperContainer()
            ->defaultMapper
            ->map(DateTimeInterface::class, '1971-11-08 13:37:42');

        self::assertSame('1971-11-08 13:37:42', $date->format('Y-m-d H:i:s'));
    }
}
