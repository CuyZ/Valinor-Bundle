<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\App\Objects;

use CuyZ\ValinorBundle\Cache\WarmupForMapper;

#[WarmupForMapper]
final class ObjectWithWarmupAttribute
{
    public string $foo;
}
