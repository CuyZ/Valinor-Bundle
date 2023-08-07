<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\App\Mapper;

use CuyZ\Valinor\Mapper\TreeMapper;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

#[AsDecorator(TreeMapper::class)]
final class TreeMapperDecoratorFromAttribute implements TreeMapper
{
    public function __construct(
        public TreeMapper $inner
    ) {}

    public function map(string $signature, mixed $source): mixed
    {
        return $this->inner->map($signature, $source);
    }
}
