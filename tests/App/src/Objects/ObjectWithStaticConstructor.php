<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\App\Objects;

final class ObjectWithStaticConstructor
{
    private function __construct(
        public string $foo,
        public string $bar,
    ) {}

    public static function create(string $foo, string $bar): self
    {
        return new self($foo, $bar);
    }
}
