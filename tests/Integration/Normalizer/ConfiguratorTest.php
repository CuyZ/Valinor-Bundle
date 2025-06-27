<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\Integration\Normalizer;

use CuyZ\ValinorBundle\Tests\Integration\IntegrationTestCase;

final class ConfiguratorTest extends IntegrationTestCase
{
    /**
     * @see \CuyZ\ValinorBundle\Tests\App\Configurator\TransformerRegistrationConfigurator
     */
    public function test_transformer_registration_configurator_is_automatically_tagged_as_normalizer_builder_configurator(): void
    {
        $result = $this->normalizerContainer()
            ->arrayNormalizer
            ->normalize(1.02);

        self::assertSame(1.44, $result);
    }
}
