<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Configurator\Attributes;

use Attribute;
use CuyZ\Valinor\MapperBuilder;

/**
 * Attribute that can be added to a mapper argument, to customize its behaviour.
 *
 * Allows permissive types `mixed` and `object` to be used during mapping.
 *
 * ```php
 * use CuyZ\Valinor\Mapper\TreeMapper;
 * use CuyZ\ValinorBundle\Configurator\Attributes\AllowPermissiveTypes;
 *
 * final class SomeService
 * {
 *     public function __construct(
 *         #[AllowPermissiveTypes]
 *         private TreeMapper $mapperWithPermissiveTypes
 *     ) {}
 * }
 * ```
 *
 * @api
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
final class AllowPermissiveTypes implements MapperBuilderConfiguratorAttribute
{
    public function configure(MapperBuilder $builder): MapperBuilder
    {
        return $builder->allowPermissiveTypes();
    }
}
