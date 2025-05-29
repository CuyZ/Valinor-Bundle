<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\App\Mapper;

use CuyZ\Valinor\Mapper\TreeMapper;
use CuyZ\Valinor\MapperBuilder;

final class MapperContainer
{
    public function __construct(
        public MapperBuilder $mapperBuilder,
        public TreeMapper $defaultMapper,
    ) {}
}
