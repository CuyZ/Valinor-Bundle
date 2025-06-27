<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\Integration\Mapper;

use CuyZ\ValinorBundle\Tests\App\Objects\ObjectWithStaticConstructor;
use CuyZ\ValinorBundle\Tests\Integration\IntegrationTestCase;

final class ConfiguratorTest extends IntegrationTestCase
{
    /**
     * @see \CuyZ\ValinorBundle\Tests\App\Configurator\ConstructorRegistrationConfigurator
     */
    public function test_constructor_registration_configurator_is_automatically_tagged_as_mapper_builder_configurator(): void
    {
        $result = $this->mapperContainer()
            ->defaultMapper
            ->map(ObjectWithStaticConstructor::class, [
                'foo' => 'foo',
                'bar' => 'bar',
            ]);

        self::assertSame('foo', $result->foo);
        self::assertSame('bar', $result->bar);
    }
}
