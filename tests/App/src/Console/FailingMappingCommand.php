<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\App\Console;

use CuyZ\Valinor\Mapper\TreeMapper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('test:command-with-failing-mapping')]
final class FailingMappingCommand extends Command
{
    public function __construct(private TreeMapper $mapper)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->mapper->map('array{foo: string, bar: string, baz: string}', [
            'foo' => 42,
            'bar' => 42,
            'baz' => 42,
        ]);

        return Command::SUCCESS;
    }
}
