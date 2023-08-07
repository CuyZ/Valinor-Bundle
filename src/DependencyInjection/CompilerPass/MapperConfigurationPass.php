<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\DependencyInjection\CompilerPass;

use CuyZ\Valinor\Mapper\TreeMapper;
use CuyZ\ValinorBundle\Configurator\Attributes\MapperBuilderConfiguratorAttribute;
use CuyZ\ValinorBundle\Configurator\AttributesConfigurator;
use CuyZ\ValinorBundle\DependencyInjection\Factory\MapperBuilderFactory;
use ReflectionAttribute;
use ReflectionNamedType;
use Symfony\Component\DependencyInjection\Compiler\AbstractRecursivePass;
use Symfony\Component\DependencyInjection\Definition;

use function array_map;
use function serialize;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

/** @internal */
final class MapperConfigurationPass extends AbstractRecursivePass
{
    protected function processValue(mixed $value, bool $isRoot = false): mixed
    {
        $value = parent::processValue($value, $isRoot);

        if (!$value instanceof Definition || !$value->isAutowired() || $value->isAbstract() || $value->getClass() === null) {
            return $value;
        }

        $constructor = $this->getConstructor($value, false);

        if ($constructor === null) {
            return $value;
        }

        foreach ($constructor->getParameters() as $parameter) {
            $type = $parameter->getType();

            if (!$type instanceof ReflectionNamedType || $type->getName() !== TreeMapper::class) {
                continue;
            }

            $attributes = $parameter->getAttributes(MapperBuilderConfiguratorAttribute::class, ReflectionAttribute::IS_INSTANCEOF);

            if ($attributes === []) {
                continue;
            }

            $instances = array_map(
                fn (ReflectionAttribute $attribute) => serialize($attribute->newInstance()),
                $attributes
            );

            $mapperBuilder = (new Definition())
                ->setFactory([
                    (new Definition(MapperBuilderFactory::class, [tagged_iterator('valinor.mapper_builder_configurator')])),
                    'create'
                ])
                ->setArguments([new Definition(AttributesConfigurator::class, [$instances])]);

            $mapper = (new Definition())->setFactory([$mapperBuilder, 'mapper']);

            $value->setArgument('$' . $parameter->name, $mapper);
        }

        return $value;
    }
}
