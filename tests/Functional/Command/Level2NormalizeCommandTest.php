<?php

declare(strict_types=1);

namespace App\Tests\Functional\Command;

use App\Command\Level2NormalizeCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class Level2NormalizeCommandTest extends KernelTestCase
{
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $command = $application->find(Level2NormalizeCommand::NAME);
        $this->commandTester = new CommandTester($command);
    }

    /** @dataProvider normalizeProvider */
    public function testNormalize(string $expected, string $filepath): void
    {
        $this->commandTester->execute([
            Level2NormalizeCommand::ARGUMENT_FILEPATH => $filepath
        ]);
        $output = $this->commandTester->getDisplay();

        self::assertSame($expected, $output);
    }

    /** @return array<mixed> */
    public function normalizeProvider(): array
    {
        $testdataDir = __DIR__ . '/_testdata/';
        return [
            'a' => [
                'expected' => \Safe\file_get_contents($testdataDir . 'a_level2.php'),
                'filepath' => $testdataDir . 'a.php',
            ]
        ];
    }
}
