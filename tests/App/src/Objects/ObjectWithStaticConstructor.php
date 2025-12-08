<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\App\Objects;

final class ObjectWithStaticConstructor
{
    /** @pure */
    private function __construct(
        public string $foo,
        public string $bar,
    ) {}

    /** @pure */
    public static function create(string $foo, string $bar): self
    {
        return new self($foo, $bar);
    }
}
