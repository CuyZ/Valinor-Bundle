<?php

use Symfony\Component\Filesystem\Filesystem;

// Removing the cache directory to reset the coverage, which is then used by
// Infection to determine which files to mutate.
(new Filesystem())->remove([__DIR__ . '/../var/cache/test/default']);
