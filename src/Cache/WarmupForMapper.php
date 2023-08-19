<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Cache;

use Attribute;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

/** @api */
#[Attribute(Attribute::TARGET_CLASS)]
final class WarmupForMapper extends Autoconfigure
{
    public function __construct(bool $autowire = true)
    {
        parent::__construct(tags: ['valinor.warmup'], autowire: $autowire);
    }
}
