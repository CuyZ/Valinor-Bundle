<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Configurator\Attributes;

use CuyZ\Valinor\MapperBuilder;

/**
 * Interface that can be implemented by an attributes aimed for a service mapper
 * argument, to customize its behaviour.
 *
 * ```php
 * use Attribute;
 * use CuyZ\Valinor\MapperBuilder;
 * use CuyZ\ValinorBundle\Configurator\Attributes\MapperBuilderConfiguratorAttribute;
 *
 * #[Attribute(Attribute::TARGET_PARAMETER)]
 * final class SomeCustomConfigurator implements MapperBuilderConfiguratorAttribute
 * {
 *     public function configure(MapperBuilder $builder): MapperBuilder
 *     {
 *         return $builder
 *             ->enableFlexibleCasting()
 *             ->allowSuperfluousKeys()
 *             ->supportDateFormats('Y/m/d');
 *     }
 * }
 * ```
 *
 * And then it can be used in a service:
 *
 * ```php
 * use CuyZ\Valinor\Mapper\TreeMapper;
 * use CuyZ\ValinorBundle\Configurator\Attributes\SomeCustomConfigurator;
 *
 * final class SomeService
 * {
 *    public function __construct(
 *       #[SomeCustomConfigurator]
 *      private TreeMapper $mapperWithCustomConfig
 *   ) {}
 * }
 *
 * @api
 */
interface MapperBuilderConfiguratorAttribute
{
    public function configure(MapperBuilder $builder): MapperBuilder;
}
