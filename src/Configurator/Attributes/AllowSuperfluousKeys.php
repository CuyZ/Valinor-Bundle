<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Configurator\Attributes;

use Attribute;
use CuyZ\Valinor\MapperBuilder;

/**
 * Attribute that can be added to a mapper argument, to customize its behaviour.
 *
 * Allows superfluous keys in source arrays, preventing errors when a value is
 * not bound to any object property/parameter or shaped array element.
 *
 * ```php
 * use CuyZ\Valinor\Mapper\TreeMapper;
 * use CuyZ\ValinorBundle\Configurator\Attributes\AllowSuperfluousKeys;
 *
 * final class SomeService
 * {
 *     public function __construct(
 *         #[AllowSuperfluousKeys]
 *         private TreeMapper $mapperWithSuperfluousKeys
 *     ) {}
 * }
 * ```
 *
 * @api
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
final class AllowSuperfluousKeys implements MapperBuilderConfiguratorAttribute
{
    public function configure(MapperBuilder $builder): MapperBuilder
    {
        return $builder->allowSuperfluousKeys();
    }
}
