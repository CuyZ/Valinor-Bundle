<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Cache;

use Attribute;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/** @api */
#[Attribute(Attribute::TARGET_CLASS)]
final class WarmupForMapper extends AutoconfigureTag
{
    public function __construct()
    {
        parent::__construct('valinor.warmup');
    }
}
