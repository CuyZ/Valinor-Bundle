<?php

namespace CuyZ\ValinorBundle\Cache;

use ReflectionMethod;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;

if ((new ReflectionMethod(WarmableInterface::class, 'warmUp'))->getNumberOfParameters() === 2) {
    // SF >= 7
    /** @internal */
    trait WarmUpCompatibility
    {
        public function warmUp(string $cacheDir, string $buildDir = null): array
        {
            $this->mapperBuilder->warmup();

            foreach ($this->classesToWarmup->getProvidedServices() as $class) {
                $this->mapperBuilder->warmup($class);
            }

            return [];
        }
    }
} else {
    // SF 5, 6
    /** @internal */
    trait WarmUpCompatibility
    {
        public function warmUp(string $cacheDir /* , string $buildDir = null */)
        {
            $this->mapperBuilder->warmup();

            foreach ($this->classesToWarmup->getProvidedServices() as $class) {
                $this->mapperBuilder->warmup($class);
            }

            return [];
        }
    }
}
