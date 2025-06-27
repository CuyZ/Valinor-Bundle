<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\Integration\Normalizer;

use CuyZ\Valinor\Normalizer\Format;
use CuyZ\ValinorBundle\Tests\Integration\IntegrationTestCase;
use DateTimeImmutable;

final class NormalizerBuilderTest extends IntegrationTestCase
{
    public function test_normalizer_builder_is_injected_and_works_properly(): void
    {
        $result = $this->normalizerContainer()
            ->normalizerBuilder
            ->normalizer(Format::array())
            ->normalize(new DateTimeImmutable('1971-11-08'));

        self::assertSame('1971-11-08T00:00:00.000000+00:00', $result);
    }
}
