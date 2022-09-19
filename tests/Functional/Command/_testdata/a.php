<?php
declare(strict_types=1);

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
