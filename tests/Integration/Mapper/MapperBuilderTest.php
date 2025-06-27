<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\Integration\Mapper;

use CuyZ\ValinorBundle\Tests\Integration\IntegrationTestCase;

final class MapperBuilderTest extends IntegrationTestCase
{
    public function test_mapper_builder_is_injected_and_works_properly(): void
    {
        $result = $this->mapperContainer()
            ->mapperBuilder
            ->mapper()
            ->map('string', 'foo');

        self::assertSame('foo', $result);
    }
}
