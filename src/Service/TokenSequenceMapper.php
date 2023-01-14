<?php

declare(strict_types=1);

namespace App\Service;

use App\Token\Token;
use App\Token\TokenNotFoundException;
use App\Token\TokenSequence;

class TokenSequenceMapper
{
    /**
     * @return array<array{
     *     t: int,
     *     o: Token,
     *     n: Token
     * }>
     */
    public function mapOriginalAndNormalized(string $originalCode, string $normalizedCode): array
    {
        $tokenSequenceOriginal = TokenSequence::fromCode($originalCode);
        $tokenSequenceNormalized = TokenSequence::fromCode($normalizedCode);

        $mapping = [];
        while (
            ($n = $this->findNextNonWhitespaceToken($tokenSequenceNormalized))
            !== null
        ) {
            $mapping[] = [
                't' => $n->phpToken->id,
                'o' => $tokenSequenceOriginal->findNextByTokenId($n->phpToken->id),
                'n' => $n,
            ];
        }

        return $mapping;
    }

    private function findNextNonWhitespaceToken(TokenSequence $tokenSequence): ?Token
    {
        try {
            return $tokenSequence->findNextByNotTokenId(T_WHITESPACE);
        } catch (TokenNotFoundException) {
            return null;
        }
    }
}