<?php
declare(strict_types=1);

namespace Foo\Bar;

// blabla
/*
 * foo
 */
/**
 * bla
 */
function foo(string $first, int $second): int
{
    $foo = $first . $second;

    return strlen($foo);
}

class Fancy
{
    private string $hello = 'world';

    public function bar(): float
    {
        return 100.0;
    }
}

(new Fancy())->bar();