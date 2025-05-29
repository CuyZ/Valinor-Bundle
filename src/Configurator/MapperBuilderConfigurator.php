<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Configurator;

use CuyZ\Valinor\MapperBuilder;

/**
 * This interface can be implemented by services that will configure the mapper
 * builder.
 *
 * If the service is autoconfigured, it will automatically be used, otherwise it
 * needs to be tagged with the tag `valinor.mapper_builder_configurator`.
 *
 * ```php
 * use CuyZ\Valinor\MapperBuilder;
 * use CuyZ\ValinorBundle\Configurator\MapperBuilderConfigurator
 *
 * final class ConstructorRegistrationConfigurator implements MapperBuilderConfigurator
 * {
 *     public function configure(MapperBuilder $builder): MapperBuilder
 *     {
 *         return $builder
 *             ->registerConstructor(SomeDTO::create(...))
 *             ->registerConstructor(SomeOtherDTO::new(...));
 *     }
 * }
 *
 * final class DateFormatConfigurator implements MapperBuilderConfigurator
 * {
 *     public function configure(MapperBuilder $builder): MapperBuilder
 *     {
 *         return $builder
 *             ->supportDateFormats('Y/m/d', 'Y-m-d H:i:s');
 *     }
 * }
 * ```
 *
 * @api
 */
interface MapperBuilderConfigurator
{
    public function configure(MapperBuilder $builder): MapperBuilder;
}
