<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\Integration\Http;

use CuyZ\Valinor\Mapper\Http\HttpRequest;
use CuyZ\ValinorBundle\Http\MapRequestAttribute;
use CuyZ\ValinorBundle\Tests\App\Controller\ControllerWithTwoMapRequestAttributes;
use CuyZ\ValinorBundle\Tests\Integration\IntegrationTestCase;
use LogicException;
use PHPUnit\Framework\Attributes\RequiresMethod;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

#[RequiresMethod(HttpRequest::class, 'fromPsr')]
final class ControllerHttpRequestMappingTest extends IntegrationTestCase
{
    public function test_can_map_get_http_request_to_controller_arguments(): void
    {
        $client = static::createClient();

        /** @see \CuyZ\ValinorBundle\Tests\App\Controller\ListArticleAction */
        $client->request('GET', '/api/authors/1337/article?status=published&sortOrder=date-desc');

        self::assertResponseIsSuccessful();
        self::assertSame(
            '{"authorId":1337,"status":"published","sort":"date-desc","uri":"\/api\/authors\/1337\/article?status=published\u0026sortOrder=date-desc"}',
            $client->getResponse()->getContent(),
        );
    }

    public function test_can_map_post_http_request_to_controller_arguments(): void
    {
        $client = static::createClient();

        /** @see \CuyZ\ValinorBundle\Tests\App\Controller\PostArticleAction */
        $client->request('POST', '/api/authors/1337/article?status=published', ['content' => 'Some content']);

        self::assertResponseIsSuccessful();
        self::assertSame(
            '{"authorId":1337,"status":"published","content":"Some content","uri":"\/api\/authors\/1337\/article?status=published"}',
            $client->getResponse()->getContent(),
        );
    }

    public function test_request_can_be_converted_to_psr(): void
    {
        $this->configureContainer(function (ContainerConfigurator $container) {
            $container->extension('valinor', [
                'http' => [
                    'convert_request_to_psr' => true,
                ],
            ]);
        });

        $client = static::createClient();

        /** @see \CuyZ\ValinorBundle\Tests\App\Controller\ControllerWithPsrRequestAction */
        $client->request('GET', '/api/psr-request');

        self::assertResponseIsSuccessful();
        self::assertSame(
            '{"uri":"\/api\/psr-request"}',
            $client->getResponse()->getContent(),
        );
    }

    public function test_mapper_configurators_from_attribute_are_called_properly(): void
    {
        $client = static::createClient();

        /** @see \CuyZ\ValinorBundle\Tests\App\Controller\ControllerWithMapRequestConfigurators */
        $client->request('GET', '/api/with-request-configurator?date=1971-11-08&extra_value=foo');

        self::assertResponseIsSuccessful();
        self::assertSame(
            '{"date":"1971-11-08"}',
            $client->getResponse()->getContent(),
        );
    }

    public function test_controller_with_two_map_request_attributes_throws_exception(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('The controller `' . ControllerWithTwoMapRequestAttributes::class . '::__invoke` can only have one attribute of type `' . MapRequestAttribute::class . '`, but found 2.');

        /** @see ControllerWithTwoMapRequestAttributes */
        $client->request('GET', '/api/two-map-request-attributes');
    }

    public function test_invalid_http_request_throws_422_error(): void
    {
        $client = static::createClient();

        /** @see \CuyZ\ValinorBundle\Tests\App\Controller\PostArticleAction */
        $client->request('POST', '/api/authors/invalid-author-id/article?status=published');

        /** @var string $content */
        $content = $client->getResponse()->getContent();

        self::assertResponseIsUnprocessable();
        self::assertStringContainsString('HTTP request is invalid, a total of 2 error(s) were found:', $content);
        self::assertStringContainsString("- authorId: value 'invalid-author-id' is not a valid integer.", $content);
        self::assertStringContainsString('- content: value *missing* is not a valid string.', $content);
    }
}
