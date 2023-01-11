<?php

declare(strict_types=1);

namespace App\Token;

use PhpToken;

class TokenSequence
{
    private function __construct(
        /** @var array<Token> */
        private array $tokens
    ){}

    public static function fromCode(string $code): self
    {
        return self::fromPhpTokens(
            PhpToken::tokenize($code)
        );
    }

    /** @param array<PhpToken> $phpTokens */
    public static function fromPhpTokens(array $phpTokens): self
    {
        return new self(array_map(
            fn(PhpToken $phpToken): Token => Token::fromPhpToken($phpToken),
            $phpTokens
        ));
    }

    private function next(): ?Token
    {
        return array_shift($this->tokens);
    }

    /** @throws TokenNotFoundException */
    public function findNext(callable $condition): Token
    {
        while (($token = $this->next()) !== null) {
            if ($condition($token)) {
                return $token;
            }
        }

        throw TokenNotFoundException::create();
    }

    /** @throws TokenNotFoundException */
    public function findNextByTokenId(int $id): Token
    {
        return $this->findNext(
            fn(Token $t): bool => $t->phpToken->is($id)
        );
    }

    /** @throws TokenNotFoundException */
    public function findNextByNotTokenId(int $id): Token
    {
        return $this->findNext(
            fn(Token $t): bool => !$t->phpToken->is($id)
        );
    }
}