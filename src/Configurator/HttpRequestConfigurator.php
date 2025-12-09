<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Configurator;

use CuyZ\Valinor\Mapper\Http\HttpRequest;
use CuyZ\Valinor\MapperBuilder;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Request;

/** @internal */
final class HttpRequestConfigurator implements \CuyZ\Valinor\Mapper\Configurator\MapperBuilderConfigurator
{
    public function configureMapperBuilder(MapperBuilder $builder): MapperBuilder
    {
        return $builder
            ->registerConverter($this->psrHttpRequestMapping(...))
            ->registerConverter($this->symfonyHttpRequestMapping(...));
    }

    /**
     * @pure
     * @template T of array
     * @param pure-callable(HttpRequest): T $next
     * @return T
     */
    private function psrHttpRequestMapping(ServerRequestInterface $psrRequest, callable $next): array
    {
        $httpRequest = HttpRequest::fromPsr($psrRequest, $psrRequest->getAttribute('_route_params', [])); // @phpstan-ignore argument.type (we know it's an array)

        return $next($httpRequest);
    }

    /**
     * @pure
     * @template T of array
     * @param pure-callable(HttpRequest): T $next
     * @return T
     */
    private function symfonyHttpRequestMapping(Request $symfonyRequest, callable $next): array
    {
        $httpRequest = new HttpRequest(
            routeParameters: $symfonyRequest->attributes->get('_route_params', []), // @phpstan-ignore argument.type (we know it's an array)
            queryParameters: $symfonyRequest->query->all(),
            bodyValues: $symfonyRequest->request->all(),
            requestObject: $symfonyRequest,
        );

        return $next($httpRequest);
    }
}
