<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\App\Cache;

use CuyZ\Valinor\Definition\ClassDefinition;
use Psr\SimpleCache\CacheInterface;

use function array_filter;
use function count;

final class CacheSpy implements CacheInterface
{
    /** @var array<string, mixed> */
    private array $calls = [];

    private bool $wasCleared = false;

    public function __construct(
        private CacheInterface $delegate
    ) {}

    public function hasCachedClassDefinition(string $className): bool
    {
        $definitions = array_filter(
            $this->calls,
            static fn (mixed $value): bool => $value instanceof ClassDefinition && $value->name() === $className
        );

        return count($definitions) > 0;
    }

    public function wasCleared(): bool
    {
        return $this->wasCleared;
    }

    public function get($key, $default = null): mixed
    {
        return $this->delegate->get($key, $default);
    }

    public function set($key, $value, $ttl = null): bool
    {
        $this->calls[$key] = $value;

        return $this->delegate->set($key, $value, $ttl);
    }

    public function delete($key): bool
    {
        return $this->delegate->delete($key);
    }

    public function clear(): bool
    {
        $this->wasCleared = true;

        return $this->delegate->clear();
    }

    public function getMultiple($keys, $default = null): iterable
    {
        return $this->delegate->getMultiple($keys, $default);
    }

    /**
     * @param iterable<mixed> $values
     */
    public function setMultiple($values, $ttl = null): bool
    {
        return $this->delegate->setMultiple($values, $ttl);
    }

    public function deleteMultiple($keys): bool
    {
        return $this->delegate->deleteMultiple($keys);
    }

    public function has($key): bool
    {
        return $this->delegate->has($key);
    }
}
