<?php

declare(strict_types=1);

namespace App\Tests\Unit\PrettyPrinter;

use App\PrettyPrinter\Level1PrettyPrinter;
use PhpParser\Comment;
use PhpParser\Node;
use PHPUnit\Framework\TestCase;

class Level1PrettyPrinterTest extends TestCase
{
    /**
     * @param array<Node> $ast
     *
     * @dataProvider prettyPrintProvider
     */
    public function testPrettyPrint(string $expected, array $ast): void
    {
        self::assertEquals($expected, (new Level1PrettyPrinter())->prettyPrint($ast));
    }

    /** @return array{expected: string, ast: array<Node>} */
    public function prettyPrintProvider(): array
    {
        return [
            'empty' => [
                'expected' => '',
                'ast' => [],
            ],
            'non empty' => [
                'expected' => '$x = 10;',
                'ast' => [
                    new Node\Stmt\Expression(
                        new Node\Expr\Assign(
                            new Node\Expr\Variable('x'),
                            new Node\Scalar\LNumber(10),
                        ),
                        [
                            'comments' => [
                                new Comment('// foo')
                            ]
                        ]
                    )
                ],
            ],
        ];
    }
}