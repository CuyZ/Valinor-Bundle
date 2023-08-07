<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Configurator\Attributes;

use Attribute;
use CuyZ\Valinor\MapperBuilder;

/**
 * Attribute that can be added to a mapper argument, to customize its behaviour.
 *
 * Configures which date formats will be supported by the mapper.
 *
 * ```php
 * use CuyZ\Valinor\Mapper\TreeMapper;
 * use CuyZ\ValinorBundle\Configurator\Attributes\SupportDateFormats;
 *
 * final class SomeService
 * {
 *     public function __construct(
 *         #[SupportDateFormats('Y-m-d', 'Y/m/d')]
 *         private TreeMapper $mapperWithCustomDateFormat
 *     ) {}
 * }
 * ```
 *
 * @api
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
final class SupportDateFormats implements MapperBuilderConfiguratorAttribute
{
    /** @var non-empty-array<non-empty-string> */
    private array $formats;

    /**
     * @param non-empty-string $format
     * @param non-empty-string ...$formats
     */
    public function __construct(string $format, string ...$formats)
    {
        $this->formats = [$format, ...$formats];
    }

    public function configure(MapperBuilder $builder): MapperBuilder
    {
        return $builder->supportDateFormats(...$this->formats);
    }
}
