<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\Integration\Mapper;

use CuyZ\ValinorBundle\Tests\Integration\IntegrationTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function preg_replace;

final class FailingMappingCommandTest extends IntegrationTestCase
{
    public function test_command_with_failing_mapping_shows_correct_message(): void
    {
        $this->configureContainer(function (ContainerConfigurator $container) {
            $container->extension('valinor', [
                'console' => [
                    'mapping_errors_to_output' => 2,
                ],
            ]);
        });

        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $tester = new ApplicationTester($application);
        $tester->run(['test:command-with-failing-mapping']);

        $result = preg_replace('/( *)$/m', '', $tester->getDisplay());

        self::assertStringContainsStringIgnoringLineEndings($this->expectedOutput(), $result ?? '');
    }

    private function expectedOutput(): string
    {
        return <<<TXT
        Mapping errors
        --------------
        
        A total of 3 errors were found while trying to map to `array{foo: string, bar: string, baz: string}`
         ------ ---------------------------------
          path   message
         ------ ---------------------------------
          foo    Value 42 is not a valid string.
          bar    Value 42 is not a valid string.
         ------ ---------------------------------
        
        and 1 more…
        TXT;
    }
}
