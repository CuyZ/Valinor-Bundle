<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Http;

use CuyZ\Valinor\MapperBuilder;

/**
 * Interface for PHP attribute that flags a controller method for automatic HTTP
 * request mapping using Valinor.
 *
 * When a controller method is annotated with an attribute implementing this
 * interface, the bundle will intercept the controller call and use the
 * arguments mapper to map the HTTP request values to the controller arguments.
 *
 * A default implementation is provided out of the box: {@see MapRequest}.
 *
 * Custom implementations can be created to adjust the mapper behavior for
 * specific controller methods:
 *
 * ```
 * use CuyZ\Valinor\Mapper\Configurator\ConvertKeysToCamelCase
 * use CuyZ\Valinor\Mapper\Configurator\RestrictKeysToSnakeCase
 * use CuyZ\Valinor\MapperBuilder;
 * use CuyZ\ValinorBundle\Http\MapRequestAttribute;
 * use Attribute;
 *
 * #[Attribute(Attribute::TARGET_METHOD)]
 * final class CustomMapRequest implements MapRequestAttribute
 * {
 *     public function __construct(
 *         private array $dateFormats = ['Y-m-d', 'Y-m-d H:i:s'],
 *         private bool $allowScalarValueCasting = false,
 *     ) {}
 *
 *     public function configureMapperBuilder(MapperBuilder $builder): MapperBuilder
 *     {
 *         // Always restrict keys to `snake_case`
 *         $builder = $builder->configureWith(new RestrictKeysToSnakeCase())
 *
 *         // Always convert keys to `camelCase`
 *         $builder = $builder->configureWith(new ConvertKeysToCamelCase());
 *
 *         $builder = $builder->supportDateFormats(...$this->dateFormats);
 *
 *         if ($this->allowScalarValueCasting) {
 *             $builder = $builder->allowScalarValueCasting();
 *         }
 *
 *         return $builder;
 *     }
 * }
 * ```
 *
 * It can then be used in place of `#[MapRequest]` on any controller method:
 *
 * ```php
 * use CuyZ\Valinor\Mapper\Http\FromBody;
 * use Symfony\Component\HttpFoundation\Response;
 * use Symfony\Component\HttpKernel\Attribute\AsController;
 * use Symfony\Component\Routing\Attribute\Route;
 *
 * #[AsController]
 * final class CreateComment
 * {
 *     #[Route('/api/comments', methods: 'POST')]
 *     #[MyAppMapRequest(dateFormats: ['d/m/Y'], allowScalarValueCasting: true)]
 *     public function __invoke(
 *         #[FromBody] string $author,
 *         #[FromBody] string $content,
 *     ): Response { /* … * / }
 * }
 * ```
 *
 * @api
 */
interface MapRequestAttribute
{
    public function configureMapperBuilder(MapperBuilder $builder): MapperBuilder;
}
