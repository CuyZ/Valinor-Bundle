<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\App\Objects;

final class BasicObjectWithArrayShape
{
    /** @var array{basicObject: BasicObject|null} */
    public array $foo;
}
