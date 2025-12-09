<div align="center">

<img src="docs/img/symfony-logo.svg" alt="Symfony logo" height="160">
<img src="docs/img/plus.svg" alt="Plus">
<img src="docs/img/valinor.svg" alt="Valinor banner" height="160">

<br>

[![Latest Stable Version](https://poser.pugx.org/cuyz/valinor-bundle/v)][link-packagist]
[![PHP Version Require](https://poser.pugx.org/cuyz/valinor-bundle/require/php)][link-packagist]

</div>

---

Symfony integration of [Valinor library].

> Valinor takes care of the construction and validation of raw inputs (JSON,
> plain arrays, etc.) into objects, ensuring a perfectly valid state. It allows
> the objects to be used without having to worry about their integrity during
> the whole application lifecycle.
>
> The validation system will detect any incorrect value and help the developers
> by providing precise and human-readable error messages.
>
> The mapper can handle native PHP types as well as other advanced types
> supported by PHPStan and Psalm like shaped arrays, generics, integer range 
> and more.
> 
> The library also provides a normalization mechanism that can help transform
> any input into a data format (JSON, CSV, …), while preserving the original
> structure.

## Installation

```bash
composer require cuyz/valinor-bundle
```

```php
// config/bundles.php

return [
    // …
    CuyZ\ValinorBundle\ValinorBundle::class => ['all' => true],
];
```

## Mapper injection

A mapper instance can be injected in any autowired service in parameters with
the type `TreeMapper`.

```php
use CuyZ\Valinor\Mapper\TreeMapper;

final class SomeAutowiredService
{
    public function __construct(
        private TreeMapper $mapper,
    ) {}
    
    public function someMethod(): void
    {
        $this->mapper->map(SomeDto::class, /* … */);
        
        // …
    }
}
```

It can also be manually injected in a service…

<details>
<summary>…using a PHP file</summary>

```php
// config/services.php

use CuyZ\Valinor\Mapper\TreeMapper;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $container
        ->services()
        ->set(\Acme\SomeService::class)
        ->args([
            service(TreeMapper::class),
        ]);
};
```
</details>

<details>
<summary>…using a YAML file</summary>

```yaml
services:
    Acme\SomeService:
        arguments:
            - '@CuyZ\Valinor\Mapper\TreeMapper'
```

</details>

---

For more granular control, a `MapperBuilder` instance can be injected instead.

```php
use CuyZ\Valinor\Mapper\MapperBuilder;

final class SomeAutowiredService
{
    public function __construct(
        private MapperBuilder $mapperBuilder,
    ) {}
    
    public function someMethod(): void
    {
        $this->mapperBuilder
            // …
            // Some mapper configuration 
            // …
            ->mapper()
            ->map(SomeDto::class, /* … */);
        
        // …
    }
}
```

## Normalizer injection

A normalizer instance can be injected in any autowired service in parameters
with a `Normalizer` type:

- `ArrayNormalizer` — injects a normalizer that transforms values to arrays and
  scalars.
- `JsonNormalizer` — injects a normalizer that transforms values to JSON.

```php
use CuyZ\Valinor\Normalizer\JsonNormalizer;

final class SomeAutowiredService
{
    public function __construct(
        private JsonNormalizer $jsonNormalizer,
    ) {}

    public function someMethod(): void
    {
        // …

        $this->jsonNormalizer->normalize($someObject);

        // …
    }
}
```

It can also be manually injected in a service…

<details>
<summary>…using a PHP file</summary>

```php
// config/services.php

use CuyZ\Valinor\Normalizer\JsonNormalizer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $container
        ->services()
        ->set(\Acme\SomeService::class)
        ->args([
            service(JsonNormalizer::class),
        ]);
};
```
</details>

<details>
<summary>…using a YAML file</summary>

```yaml
services:
    Acme\SomeService:
        arguments:
            - '@CuyZ\Valinor\Normalizer\JsonNormalizer'
```

</details>

---

For more granular control, a `NormalizerBuilder` instance can be injected
instead.

```php
use CuyZ\Valinor\Normalizer\Format;
use CuyZ\Valinor\NormalizerBuilder;

final class SomeAutowiredService
{
    public function __construct(
        private NormalizerBuilder $normalizerBuilder,
    ) {}
    
    public function someMethod(): void
    {
        $this->normalizerBuilder
            // …
            // Some normalizer configuration 
            // …
            ->normalizer(Format::array())
            ->normalize($someValue);
        
        // …
    }
}
```

## Bundle configuration

Global configuration for the bundle can be done in a package configuration file…

<details open>
<summary>…using a PHP file</summary>

```php
// config/packages/valinor.php

return static function (Symfony\Config\ValinorConfig $config): void {
    // Date formats that will be supported by the mapper by default.
    $config->mapper()->dateFormatsSupported(['Y-m-d', 'Y-m-d H:i:s']);

    // For security reasons, exceptions thrown in a constructor will not be
    // caught by the mapper unless they are specifically allowed by giving their
    // class names to the configuration below.
    $config->mapper()->allowedExceptions([
        \Webmozart\Assert\InvalidArgumentException::class,
        \App\CustomException::class,
    ]);
    
    // When enabled, controllers using `#[MapRequest]` can type-hint a PSR-7
    // `ServerRequestInterface` parameter instead of Symfony's `Request`. The
    // bundle will automatically handle the conversion.
    //
    // Note that this requires the `symfony/psr-http-message-bridge` package.
    $config->http()->convertRequestToPsr(true);

    // When a mapping error occurs during a console command, the output will
    // automatically be enhanced to show information about errors. The maximum
    // number of errors that will be displayed can be configured below, or set
    // to 0 to disable this feature entirely.
    $config->console()->mappingErrorsToOutput(15);

    // By default, mapper cache entries are stored in the build directory of the
    // application. This can be changed by setting a custom cache service.
    $config->cache()->service('app.custom_cache');

    // Cache entries representing class definitions won't be cleared when files
    // are modified during development of the application. This can be changed
    // by setting in which environments cache entries will be unvalidated.
    $config->cache()->envWhereFilesAreWatched(['dev', 'custom_env']);
};
```
</details>

<details>
<summary>…using a YAML file</summary>

```yaml
# config/packages/valinor.yaml

valinor:
    mapper:
        # Date formats that will be supported by the mapper by default.
        date_formats_supported:
            - 'Y-m-d'
            - 'Y-m-d H:i:s'

        # For security reasons, exceptions thrown in a constructor will not be
        # caught by the mapper unless they are specifically allowed by giving
        # their class names to the configuration below.
        allowed_exceptions:
            - \Webmozart\Assert\InvalidArgumentException
            - \App\CustomException,

    http:
        # When enabled, controllers using `#[MapRequest]` can type-hint a
        # PSR-7 `ServerRequestInterface` parameter instead of Symfony's
        # `Request`. The bundle will automatically handle the conversion.
        #
        # Note that this requires the `symfony/psr-http-message-bridge` package.
        convert_request_to_psr: true

    console:
        # When a mapping error occurs during a console command, the output will
        # automatically be enhanced to show information about errors. The
        # maximum number of errors that will be displayed can be configured
        # below, or set to 0 to disable this feature entirely.
        mapping_errors_to_output: 15

    cache:
        # By default, mapper cache entries are stored in the build directory of
        # the application. This can be changed by setting a custom cache
        # service.
        service: app.custom_cache

        # Cache entries representing class definitions won't be cleared when
        # files are modified during development of the application. This can be
        # changed by setting in which environments cache entries will be
        # unvalidated.
        env_where_files_are_watched: [ 'dev', 'custom_env' ]
```
</details>

## HTTP Request mapping

The bundle provides automatic mapping of HTTP request values to controller
arguments. This feature leverages Valinor's mapping capabilities to handle route
parameters, query parameters and request body data.

Lean more about HTTP request mapping [in the library
documentation](https://valinor-php.dev/latest/how-to/map-http-request/).

Note that Symfony provides a similar built-in solution, which makes use of
attributes like `#[MapQueryString]` and `#[MapRequestPayload]`. This bundle can
bring some additional features:

- No need to use attributes unless source enforcement is required.
- Ability to map advanced types like `non-empty-string`, `positive-int`,
  `int<10, 100>` and more.
- Precise error messages when a request contains invalid values.
- Easy customization of the mapping process using [mapper configurators].
- And, in the end, any other feature provided by Valinor's mapping system.

### Basic usage

Using the `#[MapRequest]` on a controller's method enables automatic arguments
mapping from route parameters, query parameters and request body data.

It works out of the box, but when it is needed to enforce a specific source for
a given parameter, one of the following attributes can be used:

- `#[FromRoute]` for route parameters
- `#[FromQuery]` for query parameters
- `#[FromBody]` for request body values

#### Example using attributes

```php
use CuyZ\ValinorBundle\Http\MapRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final class ListArticles
{
    /**
     * GET /api/authors/{authorId}/articles?status=X&page=X&limit=X
     * 
     * @param positive-int $page
     * @param int<10, 100> $limit
     */
    #[Route('/api/authors/{authorId}/articles', methods: 'GET')]
    #[MapRequest]
    public function __invoke(
        string $authorId,
        string $status,
        int $page = 1,
        int $limit = 10,
    ): Response { /* … */ }
}
```

#### Example using attributes

```php
use CuyZ\Valinor\Mapper\Http\FromQuery;
use CuyZ\Valinor\Mapper\Http\FromRoute;
use CuyZ\ValinorBundle\Http\MapRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final class ListArticles
{
    /**
     * GET /api/authors/{authorId}/articles?status=X&page=X&limit=X
     * 
     * @param positive-int $page
     * @param int<10, 100> $limit
     */
    #[Route('/api/authors/{authorId}/articles', methods: 'GET')]
    #[MapRequest]
    public function __invoke(
        // Can only be mapped from the route
        #[FromRoute] string $authorId,

        // Can only be mapped from query parameters
        #[FromQuery] string $status,
        #[FromQuery] int $page = 1,
        #[FromQuery] int $limit = 10,
    ): Response { /* … */ }
}
```

### Per-controller mapper configuration

You can customize the mapper behavior for a specific controller by passing
[mapper configurators] to the `#[MapRequest]` attribute:

```php
use CuyZ\Valinor\Mapper\Configurator\ConvertKeysToCamelCase;
use CuyZ\Valinor\Mapper\Http\FromBody;
use CuyZ\ValinorBundle\Http\MapRequest;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final class CreateAuthor
{
    #[Route('/api/authors/new', methods: 'POST')]
    #[MapRequest(new ConvertKeysToCamelCase())]
    public function __invoke(
        #[FromBody] string $name,
        #[FromBody] DateTimeInterface $birthDate,
    ): Response { /* … */ }
}
```

APIs often need to define rules concerning the keys cases passed in the request;
this can be defined using the following configurators:

- [Restricting key case configurators] — restricting keys to `camelCase`, 
  `PascalCase`, `snake_case` or `kebab-case`.
- [Converting key case configurators] — automatically converting keys to
  `camelCase` or `snake_case`.

[Restricting key case configurators]: https://valinor-php.dev/latest/how-to/use-provided-mapper-configurators/#restricting-key-case
[Converting key case configurators]: https://valinor-php.dev/latest/how-to/use-provided-mapper-configurators/#converting-key-case

### Custom request mapping attribute

When multiple controllers share the same mapper configuration (date formats, key
case rules, etc.), a custom attribute can be created to avoid repeating the same
configurators on every controller.

This is done by implementing the `MapRequestAttribute` interface directly:

```php
use Attribute;
use CuyZ\Valinor\Mapper\Configurator\ConvertKeysToCamelCase;
use CuyZ\Valinor\Mapper\Configurator\RestrictKeysToSnakeCase;
use CuyZ\Valinor\MapperBuilder;
use CuyZ\ValinorBundle\Http\MapRequestAttribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class MyAppMapRequest implements MapRequestAttribute
{
    public function __construct(
        /** @var list<non-empty-string> */
        private array $dateFormats = ['Y-m-d', 'Y-m-d H:i:s'],
        private bool $allowScalarValueCasting = false,
    ) {}

    public function configureMapperBuilder(MapperBuilder $builder): MapperBuilder
    {
        // Always restrict keys to `snake_case`
        $builder = $builder->configureWith(new RestrictKeysToSnakeCase());

        // Always convert keys to `camelCase`
        $builder = $builder->configureWith(new ConvertKeysToCamelCase());

        $builder = $builder->supportDateFormats(...$this->dateFormats);

        if ($this->allowScalarValueCasting) {
            $builder = $builder->allowScalarValueCasting();
        }

        return $builder;
    }
}
```

It can then be used in place of `#[MapRequest]` on any controller method:

```php
use CuyZ\Valinor\Mapper\Http\FromBody;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final class CreateComment
{
    #[Route('/api/comments', methods: 'POST')]
    #[MyAppMapRequest(dateFormats: ['d/m/Y'], allowScalarValueCasting: true)]
    public function __invoke(
        #[FromBody] string $author,
        #[FromBody] string $content,
    ): Response { /* … */ }
}
```

### Error handling

When mapping fails, the bundle throws an `HttpRequestMappingError` exception
with a `422 Unprocessable Entity` status code. The error message includes all
validation errors. Example:

```
HTTP request is invalid, a total of 2 error(s) were found:
- page: value 0 is not a valid positive integer.
- limit: value 150 is not a valid integer between 10 and 100.
```

### Mapping all parameters at once

Instead of mapping individual query parameters or body values to separate
parameters, the `asRoot` option can be used to map all of them at once to a
single parameter. This is useful when working with complex data structures or
when the number of parameters is large.

```php
use CuyZ\Valinor\Mapper\Http\FromQuery;
use CuyZ\Valinor\Mapper\Http\FromRoute;
use CuyZ\ValinorBundle\Http\MapRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

final readonly class ArticleFilters
{
    public function __construct(
        public string $status,
        /** @var positive-int */
        public int $page = 1,
        /** @var int<10, 100> */
        public int $limit = 10,
    ) {}
}

#[AsController]
final class ListArticles
{
    /**
     * GET /api/authors/{authorId}/articles?status=X&page=X&limit=X
     */
    #[Route('/api/authors/{authorId}/articles', methods: 'GET')]
    #[MapRequest]
    public function __invoke(
        #[FromRoute] string $authorId,
        #[FromQuery(asRoot: true)] ArticleFilters $filters,
    ): Response { /* … */ }
}
```

The same approach works with `#[FromBody(asRoot: true)]` for body values.

### Request object mapping

When a controller needs to access the original request object, it can be
directly added as an argument:

```php
use CuyZ\Valinor\Mapper\Http\FromRoute;
use CuyZ\ValinorBundle\Http\MapRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final class ListArticles
{
    #[Route('/api/authors/{authorId}/articles', methods: 'GET')]
    #[MapRequest]
    public function __invoke(
        // Request object injected automatically
        Request $request,
        
        #[FromRoute] string $authorId,
    ): Response {
        if ($request->headers->has('My-Customer-Header')) {
            // …
        }
    }
}
```

> [!NOTE]
> By enabling the [`valinor.http.convert_request_to_psr` configuration](
> #bundle-configuration), controllers can type-hint a PSR-7
> `ServerRequestInterface` parameter instead of Symfony's `Request`. The bundle
> will automatically convert the incoming Symfony request to a PSR-7 instance.
> 
> This requires the `symfony/psr-http-message-bridge` package to be installed.

## Other features

### Customizing mapper builder

Any `MapperBuilderConfigurator` service tagged with
`valinor.mapper_builder_configurator.default` will be automatically used to
customize the default mapper builder. 

```php
use CuyZ\Valinor\Mapper\Configurator\MapperBuilderConfigurator;
use CuyZ\Valinor\MapperBuilder;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('valinor.mapper_builder_configurator.default')]
final class DefaultMapperConfigurator implements MapperBuilderConfigurator
{
    public function __construct(
        /** @var non-empty-list<non-empty-string> */
        private array $dateFormats,
    ) {}

    public function configureMapperBuilder(MapperBuilder $builder): MapperBuilder
    {
        return $builder->supportDateFormats(...$this->dateFormats);
    }
}
```

### Customizing normalizer builder

Any `NormalizerBuilderConfigurator` service tagged with
`valinor.normalizer_builder_configurator.default` will be automatically used to
customize the default mapper builder.

```php
use CuyZ\Valinor\Normalizer\Configurator\NormalizerBuilderConfigurator;
use CuyZ\Valinor\NormalizerBuilder;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('valinor.normalizer_builder_configurator.default')]
final class DefaultNormalizerConfigurator implements NormalizerBuilderConfigurator
{
    public function configureNormalizerBuilder(NormalizerBuilder $builder): NormalizerBuilder
    {
        return $builder
            ->registerTransformer(
                fn (DateTimeInterface $date) => $date->format('Y-m-d')
            )
            ->registerTransformer(
                fn (\App\Domain\Money $money) => [
                    'amount' => $money->amount,
                    'currency' => $money->currency->value,
                ]
            );
    }
}
```

### Mapping errors in console commands

When running a command using Symfony Console, mapping errors will be caught to
enhance the output and give a better idea of what went wrong.

> [!NOTE]
> The maximum number of errors that will be displayed can be configured [in the
> bundle configuration](#bundle-configuration).

Example of output:

```console
$ bin/console some:command

Mapping errors
--------------

A total of 3 errors were found while trying to map to `Acme\Customer`

 -------- ------------------------------------------------------------------------- 
  path     message                                                                  
 -------- ------------------------------------------------------------------------- 
  id       Value 'John' is not a valid integer.
  name     Value 42 is not a valid string.
  email    Cannot be empty and must be filled with a value matching type `string`.  
 -------- ------------------------------------------------------------------------- 
                                                                                                                        
 [INFO] The above message was generated by the Valinor Bundle, it can be disabled
        in the configuration of the bundle.
 ```

### Cache warmup

When using Symfony's cache warmup feature — usually `bin/console cache:warmup` —
the mapper cache will be warmed up automatically for all classes that are tagged
with the tag `valinor.warmup`.

This tag can be added manually via service configuration, or automatically for
**autoconfigured classes** using the attribute `WarmupForMapper`.

```php
#[\CuyZ\ValinorBundle\Cache\WarmupForMapper]
final readonly class ClassThatWillBeWarmedUp
{
    public function __construct(
        public string $foo,
        public int $bar,
    ) {}
}
```

> [!NOTE]
> The `WarmupForMapper` attribute disables dependency injection autowiring for
> the class it is assigned to. Although autowiring a class that will be
> instantiated by a mapper makes little sense in most cases, it may still be
> needed, in which case the `$autowire` parameter of the attribute can be set to
> `true`.

### Cache clearing

When using Symfony's cache clearing feature — usually `bin/console cache:clear`
— the cache entries will be cleared automatically for all `MapperBuilder` and
`NormalizerBuilder` that are tagged respectively with `valinor.mapper_builder`
and `valinor.normalizer_builder`.

[mapper configurators]: https://valinor-php.dev/latest/how-to/use-provided-mapper-configurators/
[Valinor library]: https://github.com/CuyZ/Valinor
[link-packagist]: https://packagist.org/packages/cuyz/valinor-bundle
