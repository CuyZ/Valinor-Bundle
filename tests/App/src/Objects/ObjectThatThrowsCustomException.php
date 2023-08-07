<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\App\Objects;

final class ObjectThatThrowsCustomException
{
    public function __construct()
    {
        throw new CustomException('some custom message');
    }
}
