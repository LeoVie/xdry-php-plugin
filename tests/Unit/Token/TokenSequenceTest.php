<?php

declare(strict_types=1);

namespace App\Tests\Unit\Token;

use App\Token\Token;
use App\Token\TokenSequence;
use PHPUnit\Framework\TestCase;

class TokenSequenceTest extends TestCase
{
    /**
     * @param array<\PhpToken> $tokens
     *
     * @dataProvider findNextProvider
     */
    public function testFindNext(?\PhpToken $expected, array $tokens, callable $condition): void
    {
        self::assertSame($expected, (TokenSequence::fromPhpTokens($tokens))->findNext($condition)->phpToken);
    }

    public function findNextProvider(): \Generator
    {
        $tokens = [
            new \PhpToken(T_WHITESPACE, ''),
            new \PhpToken(T_COMMENT, ''),
            new \PhpToken(T_ARRAY, ''),
        ];
        yield [
            'expected' => $tokens[1],
            'tokens' => $tokens,
            'condition' => fn (Token $token): bool => $token->phpToken->is(T_COMMENT),
        ];

        $tokens = [
            new \PhpToken(T_OPEN_TAG, '<?php'),
            new \PhpToken(T_DECLARE, 'declare'),
            new \PhpToken(40, '('),
            new \PhpToken(T_STRING, 'strict_types'),
            new \PhpToken(61, '='),
            new \PhpToken(T_LNUMBER, '1'),
            new \PhpToken(41, ')'),
            new \PhpToken(59, ';'),
            new \PhpToken(T_FUNCTION, 'function'),
            new \PhpToken(T_WHITESPACE, ' '),
            new \PhpToken(T_STRING, 'foo'),
            new \PhpToken(40, '('),
        ];
        yield [
            'expected' => $tokens[5],
            'tokens' => $tokens,
            'condition' => fn (Token $token): bool => $token->phpToken->is(T_LNUMBER),
        ];
    }
}