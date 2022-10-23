<?php

declare(strict_types=1);

namespace App\Service;

use App\PrettyPrinter\Level1PrettyPrinter;
use PhpParser\ParserFactory;

class Level1NormalizeService
{
    public function __construct(
        private ParserFactory $parserFactory,
        private Level1PrettyPrinter $level1PrettyPrinter
    )
    {
    }

    public function normalizeFileContent(string $fileContent): string
    {
        $parser = $this->parserFactory->create(ParserFactory::PREFER_PHP7);
        $ast = $parser->parse($fileContent);

        if ($ast === null) {
            return '';
        }

        return $this->level1PrettyPrinter->prettyPrint($ast);
    }
}