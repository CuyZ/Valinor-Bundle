<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Configurator;

use CuyZ\Valinor\MapperBuilder;

/** @internal */
final class DateFormatsConfigurator implements MapperBuilderConfigurator
{
    public function __construct(
        /** @var array<non-empty-string> */
        private array $dateFormatsSupported,
    ) {}

    public function configureMapperBuilder(MapperBuilder $builder): MapperBuilder
    {
        if (count($this->dateFormatsSupported) === 0) {
            return $builder;
        }

        return $builder->supportDateFormats(...$this->dateFormatsSupported);
    }
}
