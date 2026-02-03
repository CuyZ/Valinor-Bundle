<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Configurator;

use CuyZ\Valinor\NormalizerBuilder;

/**
 * @deprecated
 *
 * The interface
 * `\CuyZ\Valinor\Normalizer\Configurator\NormalizerBuilderConfigurator` must
 * be used instead.
 *
 *  Configurator implementations will no longer be loaded automatically in the
 *  next major version. To do so, default configurators must be tagged with the
 *  tag `valinor.normalizer_builder_configurator.default`.
 *
 *  ```
 *  use CuyZ\Valinor\Normalizer\Configurator\NormalizerBuilderConfigurator;
 *  use CuyZ\Valinor\NormalizerBuilder;
 *  use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
 *
 *  #[AutoconfigureTag('valinor.normalizer_builder_configurator.default')]
 *  final class DefaultNormalizerConfigurator implements NormalizerBuilderConfigurator
 *  {
 *      public function configureNormalizerBuilder(NormalizerBuilder $builder): NormalizerBuilder
 *      {
 *          return $builder
 *              ->registerTransformer(
 *                  fn (DateTimeInterface $date) => $date->format('Y-m-d')
 *              )
 *              ->registerTransformer(
 *                  fn (\App\Domain\Money $money) => [
 *                      'amount' => $money->amount,
 *                      'currency' => $money->currency->value,
 *                  ]
 *              );
 *      }
 *  }
 *  ```
 *
 * @api
 */
interface NormalizerBuilderConfigurator extends \CuyZ\Valinor\Normalizer\Configurator\NormalizerBuilderConfigurator
{
    public function configureNormalizerBuilder(NormalizerBuilder $builder): NormalizerBuilder;
}
