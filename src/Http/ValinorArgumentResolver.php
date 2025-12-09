<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Http;

use ReflectionAttribute;
use ReflectionFunctionAbstract;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;

/**
 * This argument resolver aims to override the original argument resolving
 * mechanism when a controller is flagged with the `MapRequest` attribute.
 *
 * The new mechanism is located in:
 * @see ValinorControllerArgumentsMapper
 *
 * @internal
 */
final class ValinorArgumentResolver implements ArgumentResolverInterface
{
    public function __construct(
        private ArgumentResolverInterface $inner,
    ) {}

    /**
     * @return array<mixed>
     */
    public function getArguments(Request $request, callable $controller, ?ReflectionFunctionAbstract $reflector = null): array
    {
        if ($reflector instanceof ReflectionFunctionAbstract && $reflector->getAttributes(MapRequestAttribute::class, ReflectionAttribute::IS_INSTANCEOF) !== []) {
            return [];
        }

        return $this->inner->getArguments($request, $controller, $reflector);
    }
}
