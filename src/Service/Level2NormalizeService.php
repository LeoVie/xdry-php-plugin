<?php

declare(strict_types=1);

namespace App\Service;

use App\PrettyPrinter\Level2PrettyPrinter;
use PhpParser\ParserFactory;

class Level2NormalizeService
{
    public function __construct(
        private ParserFactory       $parserFactory,
        private Level2PrettyPrinter $level2PrettyPrinter
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

        return $this->level2PrettyPrinter->prettyPrint($ast);
    }
}