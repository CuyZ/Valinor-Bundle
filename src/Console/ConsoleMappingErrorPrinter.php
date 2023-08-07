<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Console;

use CuyZ\Valinor\Mapper\MappingError;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\Console\Style\SymfonyStyle;

/** @internal */
final class ConsoleMappingErrorPrinter
{
    public function __construct(
        private int $mappingErrorsToOutput
    ) {}

    public function __invoke(ConsoleErrorEvent $event): void
    {
        $error = $event->getError();

        if ($error instanceof MappingError) {
            $io = new SymfonyStyle($event->getInput(), $event->getOutput());

            (new MappingErrorOutput($io, $this->mappingErrorsToOutput))->print($error);
        }
    }
}
