<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\App\Http;

use Attribute;
use CuyZ\Valinor\MapperBuilder;
use CuyZ\ValinorBundle\Http\MapRequestAttribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class AnotherMapRequest implements MapRequestAttribute
{
    public function configureMapperBuilder(MapperBuilder $builder): MapperBuilder
    {
        return $builder;
    }
}
