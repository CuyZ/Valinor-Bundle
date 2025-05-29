<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\Integration;

use CuyZ\Valinor\MapperBuilder;
use PHPUnit\Framework\Attributes\RequiresMethod;

#[RequiresMethod(MapperBuilder::class, 'normalizer')]
final class NormalizerTest extends IntegrationTestCase
{
    public function test_can_use_array_normalizer(): void
    {
        $result = $this->normalizerContainer()
            ->arrayNormalizer
            ->normalize(new class () {
                public string $foo = 'foo';
                public int $bar = 42;
            });

        self::assertSame([
            'foo' => 'foo',
            'bar' => 42,
        ], $result);
    }

    public function test_can_use_json_normalizer(): void
    {
        $result = $this->normalizerContainer()
            ->jsonNormalizer
            ->normalize(new class () {
                public string $foo = 'foo';
                public int $bar = 42;
            });

        self::assertSame(
            '{"foo":"foo","bar":42}',
            $result
        );
    }
}
