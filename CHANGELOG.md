<!--- BEGIN HEADER -->
# Changelog

All notable changes to this project will be documented in this file.
<!--- END HEADER -->

## [2.3.0](https://github.com/CuyZ/Valinor-Bundle/compare/2.2.0...2.3.0) (2026-03-23)

### Notable changes

#### HTTP request mapping support

The bundle provides automatic mapping of HTTP request values to controller
arguments using attributes. This feature leverages Valinor's mapping 
capabilities to handle route parameters, query values, and request body data.

Lean more about HTTP request mapping [in the library
documentation](https://valinor-php.dev/latest/how-to/map-http-request/).

Note that Symfony provides a similar built-in solution, which makes use of
attributes like `#[MapQueryString]` and `#[MapRequestPayload]`. This bundle can
bring some additional features:

- Ability to map advanced types like `non-empty-string`, `positive-int`,
  `int<10, 100>` and more.
- Precise error messages when a request contains invalid values.
- Easy customization of the mapping process using [mapper
  configurators].
- And, in the end, any other feature provided by Valinor's mapping
  system.

##### Basic usage

Using the `#[MapRequest]` on a controller's method enables automatic
mapping.

Arguments can be mapped from different sources:

- **Route parameters** — using `#[FromRoute]` attribute
- **Query parameters** — using `#[FromQuery]` attribute
- **Request body** — using `#[FromBody]` attribute

Basic example:

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
        // Comes from the route
        #[FromRoute] string $authorId,

        // All come from query parameters
        #[FromQuery] string $status,
        #[FromQuery] int $page = 1,
        #[FromQuery] int $limit = 10,
    ): Response { /* … */ }
}
```

##### Per-controller mapper configuration

You can customize the mapper behavior for a specific controller by
passing [mapper configurators] to the `#[MapRequest]` attribute:

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

APIs often need to define rules concerning the keys cases passed in the
request; this can be defined using the following configurators:

- [Restricting key case configurators] — restricting keys to
  `camelCase`, `PascalCase`, `snake_case` or `kebab-case`.
- [Converting key case configurators] — automatically converting keys to
  `camelCase` or `snake_case`.

[Restricting key case configurators]: https://valinor-php.dev/latest/how-to/use-provided-mapper-configurators/#restricting-key-case

[Converting key case configurators]: https://valinor-php.dev/latest/how-to/use-provided-mapper-configurators/#converting-key-case

##### Custom request mapping attribute

When multiple controllers share the same mapper configuration (date
formats, key case rules, etc.), a custom attribute can be created to
avoid repeating the same configurators on every controller.

This is done by implementing the `MapRequestAttribute` interface
directly:

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
        private bool $allowSuperfluousKeys = false,
    ) {}

    public function configureMapperBuilder(MapperBuilder $builder): MapperBuilder
    {
        $builder = $builder->configureWith(
            // Always restrict keys to `snake_case`
            new RestrictKeysToSnakeCase(),
            // Always convert keys to `camelCase`
            new ConvertKeysToCamelCase(),
        );

        $builder = $builder->supportDateFormats(...$this->dateFormats);

        if ($this->allowSuperfluousKeys) {
            $builder = $builder->allowSuperfluousKeys();
        }

        return $builder;
    }
}
```

It can then be used in place of `#[MapRequest]` on any controller
method:

```php
use CuyZ\Valinor\Mapper\Http\FromBody;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final class CreateComment
{
    #[Route('/api/comments', methods: 'POST')]
    #[MyAppMapRequest(
        dateFormats: ['d/m/Y'],
        allowSuperfluousKeys: true,
    )]
    public function __invoke(
        #[FromBody] string $author,
        #[FromBody] string $content,
    ): Response { /* … */ }
}
```

##### Error handling

When mapping fails, the bundle throws an `HttpRequestMappingError`
exception with a `422 Unprocessable Entity` status code. The error
message includes all validation errors. Example:

```php
HTTP request is invalid, a total of 2 error(s) were found:
- page: value 0 is not a valid positive integer.
- limit: value 150 is not a valid integer between 10 and 100.
```

##### Mapping all parameters at once

Instead of mapping individual query parameters or body values to
separate parameters, the `mapAll` option can be used to map all of them
at once to a single parameter. This is useful when working with complex
data structures or when the number of parameters is large.

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
        #[FromQuery(mapAll: true)] ArticleFilters $filters,
    ): Response { /* … */ }
}
```

The same approach works with `#[FromBody(mapAll: true)]` for body
values.

##### Request object mapping

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

**Note** — by enabling the [`valinor.http.convert_request_to_psr`
configuration](#bundle-configuration), controllers can type-hint a PSR-7
`ServerRequestInterface` parameter instead of Symfony's `Request`. The
bundle will automatically convert the incoming Symfony request to a
PSR-7 instance.

This requires the `symfony/psr-http-message-bridge` package to be
installed.

[mapper configurators]: https://valinor-php.dev/latest/how-to/use-provided-mapper-configurators/

### Features

* Add support for HTTP request mapping using attributes ([3b4c6b](https://github.com/CuyZ/Valinor-Bundle/commit/3b4c6b3cc76a614d8b7c82e5a98b7c49ce9ac64e))
* Support upstream library configurator interfaces ([af68ac](https://github.com/CuyZ/Valinor-Bundle/commit/af68acb94cd339486f9d9e2bb2e0d6eda851b8b3))

---

## [2.2.0](https://github.com/CuyZ/Valinor-Bundle/compare/2.1.0...2.2.0) (2026-02-05)

### Features

* Add support for Symfony 8.0 ([2a0849](https://github.com/CuyZ/Valinor-Bundle/commit/2a0849a1ad06a0459e300fd135dc69031dc23bff))

### Other

* Drop support for PHP 8.1 ([89a9f8](https://github.com/CuyZ/Valinor-Bundle/commit/89a9f8bfcc938efab44c74e975452a0c2f70a937))
* Drop support for Symfony 5.4 ([7ff318](https://github.com/CuyZ/Valinor-Bundle/commit/7ff31853e483555f1b4bc4109a7e259d843947e4))

---

## [2.1.0](https://github.com/CuyZ/Valinor-Bundle/compare/2.0.0...2.1.0) (2025-11-02)

### Features

* Add support for PHP 8.5 ([b4808a](https://github.com/CuyZ/Valinor-Bundle/commit/b4808ac18f83949ccc55cf2e4c5713ef4109bd39))

### Bug Fixes

* Automatically register cache for normalizer builder ([583b3f](https://github.com/CuyZ/Valinor-Bundle/commit/583b3fe9878b7b471f43b532497dc388a9551ce7))

---

## [2.0.0](https://github.com/CuyZ/Valinor-Bundle/compare/1.0.0...2.0.0) (2025-06-27)

### ⚠ BREAKING CHANGES

* Make bundle compatible with Valinor 2.0 ([6361a7](https://github.com/CuyZ/Valinor-Bundle/commit/6361a78e84d63c3683fe3c16471a2f906f59a8b9))

---

## [1.0.0](https://github.com/CuyZ/Valinor-Bundle/compare/0.4.1...1.0.0) (2025-05-29)

First stable release 🎉

---

Note that this release also includes a breaking change: the feature that allowed
attributes to configure a `TreeMapper` directly during the injection has been
removed.

After some thoughts, this implementation was a bad idea that led to more
complexity in the bundle code base, for something that should anyway be done
differently.

There will be no replacement for this feature, and code that used it should
instead either inject an instance of `MapperBuilder` or use the
`MapperBuilderConfigurator` interface.

### ⚠ BREAKING CHANGES

* Remove `MapperBuilderConfiguratorAttribute` support ([ddcf2f](https://github.com/CuyZ/Valinor-Bundle/commit/ddcf2fbb74e5b4803fc3c3844c59b3412818b6c2))

### Features

* Register `ArrayNormalizer` and `JsonNormalizer` as services ([dcfd1c](https://github.com/CuyZ/Valinor-Bundle/commit/dcfd1c3339690d3c216eef33c1f64b130bdf75be))

---

## [0.4.1](https://github.com/CuyZ/Valinor-Bundle/compare/0.4.0...0.4.1) (2024-11-24)

### Bug Fixes

* Explicitly mark service parameter as nullable ([0232d9](https://github.com/CuyZ/Valinor-Bundle/commit/0232d9f5869e018ecd23da351e8daa3debf1fd02))

---

## [0.4.0](https://github.com/CuyZ/Valinor-Bundle/compare/0.3.0...0.4.0) (2024-11-21)

### Features

* Add support for PHP 8.4 ([52fe0a](https://github.com/CuyZ/Valinor-Bundle/commit/52fe0a5d14d01a556b4bc6bebbe91bb696e4f15b))

---

## [0.3.0](https://github.com/CuyZ/Valinor-Bundle/compare/0.2.3...0.3.0) (2024-05-27)

### Features

* Add alias for `MapperBuilder` ([23e8c6](https://github.com/CuyZ/Valinor-Bundle/commit/23e8c6800867918034bd85a9d901bf6414d2b43e))

### Bug Fixes

* Solve deprecation message regarding warm-up class ([2d6bba](https://github.com/CuyZ/Valinor-Bundle/commit/2d6bba8538dd47bb569b3b5a2dd10c0f363cb2d8))

### Other

* Drop support for PHP 8.0 ([d631b2](https://github.com/CuyZ/Valinor-Bundle/commit/d631b22bca9d9b66076707234b03815436b89eaa))

---

## [0.2.3](https://github.com/CuyZ/Valinor-Bundle/compare/0.2.2...0.2.3) (2024-01-13)

### Bug Fixes

* Set class name in compiler pass definition ([3ef094](https://github.com/CuyZ/Valinor-Bundle/commit/3ef094a975d540b9a038a17a1811a08827c2ad75))

### Other

* Watch files in test environment by default ([2abfbb](https://github.com/CuyZ/Valinor-Bundle/commit/2abfbb0c9c269b3e630c3f217edb89e2ff48b8a3))

---

## [0.2.2](https://github.com/CuyZ/Valinor-Bundle/compare/0.2.1...0.2.2) (2023-10-11)

### Bug Fixes

* Correctly fetch Kernel environment in services configuration ([882778](https://github.com/CuyZ/Valinor-Bundle/commit/882778f3c5d376925794e3e717787daaa0e95872))

---

## [0.2.1](https://github.com/CuyZ/Valinor-Bundle/compare/0.2.0...0.2.1) (2023-09-08)

### Bug Fixes

* Disable injection autowiring for `WarmupForMapper` attribute ([c5a984](https://github.com/CuyZ/Valinor-Bundle/commit/c5a98407b85289b2883edd97e49d8cd869bb2922))

---

## [0.2.0](https://github.com/CuyZ/Valinor-Bundle/compare/0.1.0...v0.2.0) (2023-08-25)

### Features

* Add support for PHP 8.3 ([0c0735](https://github.com/CuyZ/Valinor-Bundle/commit/0c073572cbc05035240ed95e99b653302d284a05))

### Bug Fixes

* Disable injection autowiring for `WarmupForMapper` attribute ([2dc2b9](https://github.com/CuyZ/Valinor-Bundle/commit/2dc2b9301745a2633202779bf66325bd559c895f))
* Run cache warmup even if no class was provided ([0fa125](https://github.com/CuyZ/Valinor-Bundle/commit/0fa125b52512c56ff93ed39191c2446e8e6b6f98))

---

## [0.1.0](https://github.com/CuyZ/Valinor-Bundle/commit/4b2ae168f3b3332043a21c34683fd22bac33803e) (2023-08-07)

Initial release 🎉
