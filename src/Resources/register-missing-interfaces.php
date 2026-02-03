<?php

namespace CuyZ\Valinor\Mapper\Configurator;

use CuyZ\Valinor\MapperBuilder;

if (! interface_exists(MapperBuilderConfigurator::class)) {
    /**
     * @api
     */
    interface MapperBuilderConfigurator
    {
        public function configureMapperBuilder(MapperBuilder $builder): MapperBuilder;
    }
}

namespace CuyZ\Valinor\Normalizer\Configurator;

use CuyZ\Valinor\NormalizerBuilder;

if (! interface_exists(NormalizerBuilderConfigurator::class)) {
    /**
     * @api
     */
    interface NormalizerBuilderConfigurator
    {
        public function configureNormalizerBuilder(NormalizerBuilder $builder): NormalizerBuilder;
    }
}
