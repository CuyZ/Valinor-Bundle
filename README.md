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
````

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

    // When a mapping error occurs during a console command, the output will
    // automatically be enhanced to show information about errors. The maximum
    // number of errors that will be displayed can be configured below, or set
    // to 0 to disable this feature entirely.
    $config->console()->mappingErrorsToOutput(15);

    // By default, mapper cache entries are stored in the filesystem. This can
    // be changed by setting the name of a PSR-16 cache service below.
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

    console:
        # When a mapping error occurs during a console command, the output will
        # automatically be enhanced to show information about errors. The
        # maximum number of errors that will be displayed can be configured
        # below, or set to 0 to disable this feature entirely.
        mapping_errors_to_output: 15

    cache:
        # By default, mapper cache entries are stored in the filesystem. This
        # can be changed by setting the name of a PSR-16 cache service below.
        service: app.custom_cache

        # Cache entries representing class definitions won't be cleared when
        # files are modified during development of the application. This can be
        # changed by setting in which environments cache entries will be
        # unvalidated.
        env_where_files_are_watched: [ 'dev', 'custom_env' ]
```
</details>

## Other features

### Customizing mapper builder

A service can customize the mapper builder by implementing the interface
`MapperBuilderConfigurator`.

> [!NOTE]
> If this service is autoconfigured, it will automatically be used, otherwise it
> needs to be tagged with the tag `valinor.mapper_builder_configurator`.

```php
use CuyZ\Valinor\MapperBuilder;
use CuyZ\ValinorBundle\Configurator\MapperBuilderConfigurator

final class ConstructorRegistrationConfigurator implements MapperBuilderConfigurator
{
    public function configure(MapperBuilder $builder): MapperBuilder
    {
        return $builder
            ->registerConstructor(SomeDTO::create(...))
            ->registerConstructor(SomeOtherDTO::new(...));
    }
}

final class DateFormatConfigurator implements MapperBuilderConfigurator
{
    public function configure(MapperBuilder $builder): MapperBuilder
    {
        return $builder
            ->supportDateFormats('Y/m/d', 'Y-m-d H:i:s');
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

[Valinor library]: https://github.com/CuyZ/Valinor
[link-packagist]: https://packagist.org/packages/cuyz/valinor-bundle
