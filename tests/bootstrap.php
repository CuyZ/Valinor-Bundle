<?php

// Symfony7 remove this file
// @see https://github.com/symfony/symfony/issues/53812#issuecomment-1962311843

declare(strict_types=1);

use Symfony\Component\ErrorHandler\ErrorHandler;

require dirname(__DIR__) . '/vendor/autoload.php';

set_exception_handler([new ErrorHandler(), 'handleException']);
