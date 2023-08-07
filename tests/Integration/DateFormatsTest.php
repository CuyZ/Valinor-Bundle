<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\Integration;

use Composer\InstalledVersions;
use CuyZ\Valinor\Mapper\MappingError;
use DateTimeInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function version_compare;

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

    public function test_date_format_registered_with_attribute_is_respected(): void
    {
        $this->checkVersion();

        $date = $this->mapperContainer()
            ->mapperWithCustomDateFormat
            ->map(DateTimeInterface::class, '1971-11-08');

        self::assertSame('1971-11-08', $date->format('Y-m-d'));
    }

    public function test_date_format_registered_with_attribute_overrides_date_format_registered_in_config(): void
    {
        $this->checkVersion();

        try {
            $this->mapperContainer()
                ->mapperWithCustomDateFormat
                ->map(DateTimeInterface::class, '1971-11-08 13:37:42');
        } catch (MappingError $exception) {
            $error = $exception->node()->messages()[0];

            self::assertSame("Value '1971-11-08 13:37:42' does not match any of the following formats: `Y-m-d`.", (string)$error);
        }
    }

    private function checkVersion(): void
    {
        // @phpstan-ignore-next-line
        if (version_compare(InstalledVersions::getVersion('cuyz/valinor'), '1.5.0', '<=')) {
            // @see https://github.com/CuyZ/Valinor/commit/eaa1283ae15e00b64552abcd28c0236033577992
            // @see https://github.com/CuyZ/Valinor/commit/5c89c66f0bdd87b4b50c401d1de22b72f4fde513
            // @see https://github.com/CuyZ/Valinor/commit/e8ca2ff93c241034789d9af938bd7f5bbffd3d37
            self::markTestSkipped('A bug prevents this test from passing on Valinor 1.5.0 and below');
        }
    }
}
