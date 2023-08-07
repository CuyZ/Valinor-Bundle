<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\Integration;

final class MappingTest extends IntegrationTestCase
{
    public function test_default_mapper_is_injected_and_works_properly(): void
    {
        $result = $this->mapperContainer()
            ->defaultMapper
            ->map('string', 'foo');

        self::assertSame('foo', $result);
    }

    public function test_mapper_with_flexible_casting_attribute_is_injected_and_works_properly(): void
    {
        $result = $this->mapperContainer()
            ->mapperWithFlexibleCasting
            ->map('string', 42);

        self::assertSame('42', $result);
    }

    public function test_mapper_with_superfluous_keys_attribute_is_injected_and_works_properly(): void
    {
        $result = $this->mapperContainer()
            ->mapperWithSuperfluousKeys
            ->map('array{foo: string}', ['foo' => 'foo', 'bar' => 'bar']);

        self::assertSame(['foo' => 'foo'], $result);
    }

    public function test_mapper_with_permissive_types_attribute_is_injected_and_works_properly(): void
    {
        $result = $this->mapperContainer()
            ->mapperWithPermissiveTypes
            ->map('mixed', 'foo');

        self::assertSame('foo', $result);
    }
}
