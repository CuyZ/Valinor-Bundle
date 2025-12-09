<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Http;

use Closure;
use CuyZ\Valinor\Mapper\Http\HttpRequest;
use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\MapperBuilder;
use CuyZ\ValinorBundle\Configurator\HttpRequestConfigurator;
use LogicException;
use ReflectionFunction;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;

use function is_a;

/**
 * Hooks up in the Symfony HTTP Kernel process to override the controller and
 * map its arguments using Valinor.
 *
 * The controller must be flagged with {@see MapRequestAttribute}
 *
 * The mapping of an HTTP request object is done in the following configurator:
 * {@see HttpRequestConfigurator}
 *
 * Example:
 *
 * ```
 * use CuyZ\Valinor\Mapper\Http\FromBody;
 * use CuyZ\Valinor\Mapper\Http\FromRoute;
 * use CuyZ\ValinorBundle\Http\MapRequest;
 * use Symfony\Component\HttpFoundation\Response;
 * use Symfony\Component\Routing\Attribute\Route;
 *
 * final class PostComment
 * {
 *     #[Route('/api/posts/{postId}/comments', methods: 'POST')]
 *     #[MapRequest]
 *     public function __invoke(
 *         // Comes from the route
 *         #[FromRoute] string $postId,
 *
 *         // Both come from body payload
 *         #[FromBody] string $author,
 *         #[FromBody] string $content,
 *     ): Response { … }
 * }
 * ```
 *
 * @internal
 */
final class ValinorControllerArgumentsMapper
{
    // @deprecated remove in 3.0
    private static bool $httpRequestExists;

    public function __construct(
        private MapperBuilder $mapperBuilder,
        private bool $convertRequestToPsr,
    ) {}

    public function __invoke(ControllerArgumentsEvent $event): void
    {
        $mapRequestAttributes = $this->getMapRequestAttribute($event->getAttributes());

        if ($mapRequestAttributes === []) {
            return;
        }

        if (count($mapRequestAttributes) > 1) {
            $this->throwTooManyAttributesException(count($mapRequestAttributes), $event->getController());
        }

        self::$httpRequestExists ??= class_exists(HttpRequest::class);

        if (! self::$httpRequestExists) {
            throw new LogicException('You must update `cuyz/valinor` package to use the HTTP request mapping feature.');
        }

        $mapperBuilder = $mapRequestAttributes[0]->configureMapperBuilder($this->mapperBuilder);

        $request = $event->getRequest();

        if ($this->convertRequestToPsr) {
            $request = (new PsrHttpFactory())->createRequest($request);
        }

        try {
            $arguments = $mapperBuilder->argumentsMapper()->mapArguments($event->getController(), $request);

            $event->setArguments($arguments);
        } catch (MappingError $error) {
            throw new HttpRequestMappingError($error);
        }
    }

    /**
     * @param array<class-string, list<object>> $attributesList
     * @return list<MapRequestAttribute>
     */
    private function getMapRequestAttribute(array $attributesList): array
    {
        $mapRequestAttributes = [];

        foreach ($attributesList as $name => $attributes) {
            if (is_a($name, MapRequestAttribute::class, true)) {
                /** @var list<MapRequestAttribute> $attributes */
                $mapRequestAttributes = [...$mapRequestAttributes, ...$attributes];
            }
        }

        return $mapRequestAttributes;
    }

    private function throwTooManyAttributesException(int $count, callable $controller): never
    {
        $reflection = new ReflectionFunction(Closure::fromCallable($controller));
        $signature = $reflection->getName();

        if ($reflection->getClosureScopeClass() !== null) {
            $signature = $reflection->getClosureScopeClass()->getName() . '::' . $signature;
        }

        throw new LogicException("The controller `$signature` can only have one attribute of type `" . MapRequestAttribute::class . "`, but found $count.");
    }
}
