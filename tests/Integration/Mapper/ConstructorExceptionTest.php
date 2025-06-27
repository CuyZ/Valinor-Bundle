<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\Integration\Mapper;

use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\ValinorBundle\Tests\App\Objects\CustomException;
use CuyZ\ValinorBundle\Tests\App\Objects\ObjectThatThrowsCustomException;
use CuyZ\ValinorBundle\Tests\Integration\IntegrationTestCase;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

final class ConstructorExceptionTest extends IntegrationTestCase
{
    public function test_custom_exception_that_is_allowed_in_mapper_builder_is_caught_by_mapper(): void
    {
        $this->configureContainer(function (ContainerConfigurator $container) {
            $container->extension('valinor', [
                'mapper' => [
                    'allowed_exceptions' => [CustomException::class],
                ],
            ]);
        });

        try {
            $this->mapperContainer()
                ->defaultMapper
                ->map(ObjectThatThrowsCustomException::class, []);
        } catch (MappingError $exception) {
            $error = $exception->messages()->toArray()[0];

            self::assertSame('some custom message', (string)$error);
        }
    }
}
