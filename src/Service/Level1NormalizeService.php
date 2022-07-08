<?php

declare(strict_types=1);

namespace App\Service;

use LeoVie\PhpTokenNormalize\Model\TokenSequence;
use LeoVie\PhpTokenNormalize\Service\TokenSequenceNormalizer;

class Level1NormalizeService
{
    public function __construct(
        private TokenSequenceNormalizer $tokenSequenceNormalizer
    )
    {
    }

    public function normalizeFileContent(string $fileContent): string
    {
        $tokenSequence = TokenSequence::create(\PhpToken::tokenize($fileContent));

        $normalizedTokenSequence = $this->tokenSequenceNormalizer->normalizeLevel1($tokenSequence);

        return $normalizedTokenSequence->toCode();
    }
}