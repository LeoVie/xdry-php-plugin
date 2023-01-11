<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Level2NormalizeService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Level2NormalizeCommand extends Command
{
    public const NAME = 'normalize:level-2';
    public const ARGUMENT_FILEPATH = 'filepath';
    protected static $defaultName = self::NAME;

    public function __construct(private readonly Level2NormalizeService $level2NormalizeService)
    {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this->addArgument(
            self::ARGUMENT_FILEPATH,
            InputArgument::REQUIRED
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $filepath */
        $filepath = $input->getArgument(self::ARGUMENT_FILEPATH);

        $output->write(
            \Safe\json_encode(
                $this->level2NormalizeService->normalize(
                    \Safe\file_get_contents($filepath)
                )
            )
        );

        return Command::SUCCESS;
    }
}