<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Configurator;

use CuyZ\Valinor\MapperBuilder;

/**
 * @deprecated
 *
 * The interface `\CuyZ\Valinor\Mapper\Configurator\MapperBuilderConfigurator`
 * must be used instead.
 *
 * Configurator implementations will no longer be loaded automatically in the
 * next major version. To do so, default configurators must be tagged with the
 * tag `valinor.mapper_builder_configurator.default`.
 *
 * ```
 * use CuyZ\Valinor\Mapper\Configurator\MapperBuilderConfigurator;
 * use CuyZ\Valinor\MapperBuilder;
 * use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
 *
 * #[AutoconfigureTag('valinor.mapper_builder_configurator.default')]
 * final class DefaultMapperConfigurator implements MapperBuilderConfigurator
 * {
 *     public function configureMapperBuilder(MapperBuilder $builder): MapperBuilder
 *     {
 *         return $builder
 *             ->allowScalarValueCasting()
 *             ->registerConstructor(
 *                 \App\Domain\CustomerId::fromString(...),
 *             );
 *     }
 * }
 * ```
 *
 * @api
 */
interface MapperBuilderConfigurator extends \CuyZ\Valinor\Mapper\Configurator\MapperBuilderConfigurator
{
    public function configureMapperBuilder(MapperBuilder $builder): MapperBuilder;
}
