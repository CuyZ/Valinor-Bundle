<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\App\Cache;

use CuyZ\Valinor\Cache\Cache;
use CuyZ\Valinor\Cache\CacheEntry;
use CuyZ\Valinor\Definition\ClassDefinition;

use function array_filter;
use function call_user_func;
use function count;

/**
 * @implements Cache<mixed>
 */
final class CacheSpy implements Cache
{
    /** @var array<string, mixed> */
    private array $calls = [];

    private bool $wasCleared = false;

    public function __construct(
        /** @var Cache<mixed> */
        private Cache $delegate
    ) {}

    public function hasCachedClassDefinition(string $className): bool
    {
        $definitions = array_filter(
            $this->calls,
            fn (mixed $value) => $value instanceof ClassDefinition && $value->name === $className,
        );

        return count($definitions) > 0;
    }

    public function get(string $key, mixed ...$arguments): mixed
    {
        return $this->delegate->get($key, ...$arguments);
    }

    public function set(string $key, CacheEntry $entry): void
    {
        $this->calls[$key] = call_user_func(eval('return ' . $entry->code . ';'));

        $this->delegate->set($key, $entry);
    }

    public function wasCleared(): bool
    {
        return $this->wasCleared;
    }

    public function clear(): void
    {
        $this->wasCleared = true;

        $this->delegate->clear();
    }
}
