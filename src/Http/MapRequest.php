<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Http;

use Attribute;
use CuyZ\Valinor\Mapper\Configurator\MapperBuilderConfigurator;
use CuyZ\Valinor\MapperBuilder;

/**
 * This attribute can be used on a controller method to activate automatic
 * mapping of the HTTP request values to the controller arguments.
 *
 * Instances of {@see MapperBuilderConfigurator} can be passed to the
 * constructor to customize the mapper builder for this specific controller
 * method. This is useful for applying per-route configuration such as case
 * conversion or allowed case strategies:
 *
 * ```
 * use CuyZ\Valinor\Mapper\Http\FromQuery;
 * use CuyZ\Valinor\Mapper\Http\FromRoute;
 * use CuyZ\ValinorBundle\Http\MapRequest;
 * use Symfony\Component\HttpFoundation\Response;
 * use Symfony\Component\HttpKernel\Attribute\AsController;
 * use Symfony\Component\Routing\Attribute\Route;
 *
 * #[AsController]
 * final class ListArticles
 * {
 *     /**
 *      * GET /api/authors/{authorId}/articles?status=X&sort=X&page=X&limit=X
 *      *
 *      * @param positive-int $page
 *      * @param int<10, 100> $limit
 *      * /
 *     #[Route('/api/authors/{authorId}/articles', methods: 'GET')]
 *     #[MapRequest]
 *     public function __invoke(
 *         // Comes from the route
 *         #[FromRoute] string $authorId,
 *
 *         // All come from query parameters
 *         #[FromQuery] string $status,
 *         #[FromQuery] string $sort,
 *         #[FromQuery] int $page = 1,
 *         #[FromQuery] int $limit = 10,
 *     ): Response { /* … * / }
 * }
 * ```
 *
 * For more advanced use cases, a custom attribute can be created by
 * implementing {@see MapRequestAttribute} directly.
 *
 * @api
 */
#[Attribute(Attribute::TARGET_METHOD)]
final class MapRequest implements MapRequestAttribute
{
    /** @var list<MapperBuilderConfigurator> */
    private array $configurators;

    /**
     * @no-named-arguments
     */
    public function __construct(MapperBuilderConfigurator ...$configurators)
    {
        $this->configurators = $configurators;
    }

    public function configureMapperBuilder(MapperBuilder $builder): MapperBuilder
    {
        foreach ($this->configurators as $configurator) {
            $builder = $configurator->configureMapperBuilder($builder);
        }

        return $builder;
    }
}
