<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\App\Cache;

use CuyZ\Valinor\Cache\Cache;
use CuyZ\Valinor\Cache\CacheEntry;
use CuyZ\Valinor\Definition\ClassDefinition;

use function call_user_func;

/**
 * @implements Cache<mixed>
 */
final class CacheSpy implements Cache
{
    /** @var array<string, string> */
    private array $entries = [];

    private bool $wasCleared = false;

    public function __construct(
        /** @var Cache<mixed> */
        private Cache $delegate
    ) {}

    public function hasCachedClassDefinition(string $className): bool
    {
        foreach ($this->entries as $entry) {
            $result = call_user_func(eval("return $entry;"));

            if ($result instanceof ClassDefinition && $result->name === $className) {
                return true;
            }
        }

        return false;
    }

    public function get(string $key, mixed ...$arguments): mixed
    {
        return $this->delegate->get($key, ...$arguments);
    }

    public function set(string $key, CacheEntry $entry): void
    {
        $this->entries[$key] = $entry->code;

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
