<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Configurator;

use CuyZ\Valinor\MapperBuilder;
use CuyZ\ValinorBundle\Configurator\Attributes\MapperBuilderConfiguratorAttribute;

/** @internal */
final class AttributesConfigurator implements MapperBuilderConfigurator
{
    public function __construct(
        /** @var array<string> */
        private array $attributes = []
    ) {}

    public function configure(MapperBuilder $builder): MapperBuilder
    {
        foreach ($this->attributes as $attribute) {
            $attribute = unserialize($attribute);

            assert($attribute instanceof MapperBuilderConfiguratorAttribute);

            $builder = $attribute->configure($builder);
        }

        return $builder;
    }
}
