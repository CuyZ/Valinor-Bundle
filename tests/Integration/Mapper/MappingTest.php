<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\Integration\Mapper;

use CuyZ\ValinorBundle\Tests\Integration\IntegrationTestCase;

final class MappingTest extends IntegrationTestCase
{
    public function test_default_mapper_is_injected_and_works_properly(): void
    {
        $result = $this->mapperContainer()
            ->defaultMapper
            ->map('string', 'foo');

        self::assertSame('foo', $result);
    }
}
