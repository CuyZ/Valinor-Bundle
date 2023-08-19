<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\Unit\Cache;

use CuyZ\ValinorBundle\Cache\WarmupForMapper;
use PHPUnit\Framework\TestCase;

final class WarmupForMapperTest extends TestCase
{
    public function test_autowiring_attribute_is_set_correctly(): void
    {
        $attributeWithoutArgument = new WarmupForMapper();
        $attributeWithAutowiring = new WarmupForMapper(true);
        $attributeWithoutAutowiring = new WarmupForMapper(false);

        self::assertTrue($attributeWithoutArgument->autowire);
        self::assertTrue($attributeWithAutowiring->autowire);
        self::assertFalse($attributeWithoutAutowiring->autowire);
    }
}
