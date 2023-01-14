<?php

declare(strict_types=1);

namespace App\Token;

class TokenNotFoundException extends \Exception
{
    public static function create(): self
    {
        return new self();
    }
}