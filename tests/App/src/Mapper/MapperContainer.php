<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\App\Mapper;

use CuyZ\Valinor\Mapper\TreeMapper;
use CuyZ\ValinorBundle\Configurator\Attributes\AllowPermissiveTypes;
use CuyZ\ValinorBundle\Configurator\Attributes\AllowSuperfluousKeys;
use CuyZ\ValinorBundle\Configurator\Attributes\EnableFlexibleCasting;
use CuyZ\ValinorBundle\Configurator\Attributes\SupportDateFormats;
use CuyZ\ValinorBundle\Tests\App\Configurator\DateConfiguratorAttribute;

final class MapperContainer
{
    public function __construct(
        public TreeMapper $defaultMapper,
        #[EnableFlexibleCasting]
        public TreeMapper $mapperWithFlexibleCasting,
        #[AllowSuperfluousKeys]
        public TreeMapper $mapperWithSuperfluousKeys,
        #[AllowPermissiveTypes]
        public TreeMapper $mapperWithPermissiveTypes,
        #[SupportDateFormats('Y-m-d')]
        public TreeMapper $mapperWithCustomDateFormat,
        #[DateConfiguratorAttribute]
        public TreeMapper $mapperWithCustomDateConfiguratorAttribute,
    ) {}
}
