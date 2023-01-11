<?php

declare(strict_types=1);

namespace App\Token;

class Token implements \JsonSerializable
{
    private function __construct(
        public readonly \PhpToken $phpToken
    )
    {
    }

    public static function fromPhpToken(\PhpToken $phpToken): self
    {
        return new self($phpToken);
    }

    /**
     * @return array{
     *     code: string,
     *     line: int,
     *     pos: int,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'code' => $this->phpToken->text,
            'line' => $this->phpToken->line,
            'pos' => $this->phpToken->pos,
        ];
    }
}