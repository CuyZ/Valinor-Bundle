<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Configurator;

use CuyZ\Valinor\Mapper\Tree\Message\MessageBuilder;
use CuyZ\Valinor\MapperBuilder;
use Throwable;

use function is_a;

/** @internal */
final class AllowedExceptionsConfigurator implements MapperBuilderConfigurator
{
    public function __construct(
        /** @var array<class-string> */
        private array $allowedExceptions,
    ) {}

    public function configure(MapperBuilder $builder): MapperBuilder
    {
        if (count($this->allowedExceptions) === 0) {
            return $builder;
        }

        return $builder->filterExceptions(function (Throwable $exception) {
            foreach ($this->allowedExceptions as $allowedException) {
                if (is_a($exception, $allowedException, true)) {
                    return MessageBuilder::from($exception);
                }
            }

            throw $exception;
        });
    }
}
