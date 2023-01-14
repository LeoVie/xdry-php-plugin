<?php

declare(strict_types=1);

namespace App\Service;

use App\PrettyPrinter\Level1PrettyPrinter;
use App\Token\Token;
use PhpParser\ParserFactory;

class Level1NormalizeService
{
    public function __construct(
        private readonly ParserFactory       $parserFactory,
        private readonly Level1PrettyPrinter $level1PrettyPrinter,
        private readonly TokenSequenceMapper $tokenSequenceMapper,
    )
    {
    }

    /**
     * @return array<array{
     *     o: Token,
     *     n: Token
     * }>
     */
    public function normalize(string $fileContent): array
    {
        $parser = $this->parserFactory->create(ParserFactory::PREFER_PHP7);
        $ast = $parser->parse($fileContent);

        if ($ast === null) {
            return [];
        }

        return $this->tokenSequenceMapper->mapOriginalAndNormalized(
            $fileContent,
            '<?php ' . $this->level1PrettyPrinter->prettyPrint($ast)
        );
    }
}