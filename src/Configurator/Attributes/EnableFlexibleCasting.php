<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Configurator\Attributes;

use Attribute;
use CuyZ\Valinor\MapperBuilder;

/**
 * Attribute that can be added to a mapper argument, to customize its behaviour.
 *
 * Changes several behaviours of the mapper concerning type flexibility:
 *
 * - Scalar types will accept non-strict values; for instance an integer type
 *   will accept any valid numeric value like the *string* "42"
 * - List type will accept non-incremental keys.
 * - If a value is missing in a source for a node that accepts `null`, the node
 *   will be filled with `null`.
 * - Array and list types will convert `null` or missing values to an empty
 *   array.
 *
 * ```php
 * use CuyZ\Valinor\Mapper\TreeMapper;
 * use CuyZ\ValinorBundle\Configurator\Attributes\EnableFlexibleCasting;
 *
 * final class SomeService
 * {
 *     public function __construct(
 *         #[EnableFlexibleCasting]
 *         private TreeMapper $mapperWithFlexibleCasting
 *     ) {}
 * }
 * ```
 *
 * @api
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
final class EnableFlexibleCasting implements MapperBuilderConfiguratorAttribute
{
    public function configure(MapperBuilder $builder): MapperBuilder
    {
        return $builder->enableFlexibleCasting();
    }
}
