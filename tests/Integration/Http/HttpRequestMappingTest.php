<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\Integration\Http;

use CuyZ\Valinor\Mapper\Http\FromBody;
use CuyZ\Valinor\Mapper\Http\FromQuery;
use CuyZ\Valinor\Mapper\Http\FromRoute;
use CuyZ\Valinor\Mapper\Http\HttpRequest;
use CuyZ\ValinorBundle\Tests\Integration\IntegrationTestCase;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\Attributes\RequiresMethod;
use Symfony\Component\HttpFoundation\Request;

#[RequiresMethod(HttpRequest::class, 'fromPsr')]
final class HttpRequestMappingTest extends IntegrationTestCase
{
    public function test_can_map_symfony_request(): void
    {
        $request = Request::create('/ping');
        $request->initialize(
            query: ['queryParam' => 'foo'],
            request: ['bodyValue' => 'bar'],
            attributes: ['_route_params' => ['routeParam' => 'baz']],
        );

        $class = new class () {
            #[FromQuery]
            public string $queryParam;

            #[FromBody]
            public string $bodyValue;

            #[FromRoute]
            public string $routeParam;
        };

        $result = $this->mapperContainer()->defaultMapper->map($class::class, $request);

        self::assertSame('foo', $result->queryParam);
        self::assertSame('bar', $result->bodyValue);
        self::assertSame('baz', $result->routeParam);
    }

    public function test_can_map_psr_request(): void
    {
        $request = (new ServerRequest('GET', '/ping'))
            ->withQueryParams(['queryParam' => 'foo'])
            ->withParsedBody(['bodyValue' => 'bar'])
            ->withAttribute('_route_params', ['routeParam' => 'baz']);

        $class = new class () {
            #[FromQuery]
            public string $queryParam;

            #[FromBody]
            public string $bodyValue;

            #[FromRoute]
            public string $routeParam;
        };

        $result = $this->mapperContainer()->defaultMapper->map($class::class, $request);

        self::assertSame('foo', $result->queryParam);
        self::assertSame('bar', $result->bodyValue);
        self::assertSame('baz', $result->routeParam);
    }
}
