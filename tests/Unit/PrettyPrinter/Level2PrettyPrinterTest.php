<?php

declare(strict_types=1);

namespace App\Tests\Unit\PrettyPrinter;

use App\PrettyPrinter\Level2PrettyPrinter;
use PhpParser\Comment;
use PhpParser\Node;
use PHPUnit\Framework\TestCase;

class Level2PrettyPrinterTest extends TestCase
{
    /**
     * @param array<Node> $ast
     *
     * @dataProvider prettyPrintIntoTreeProvider
     */
    public function testPrettyPrintIntoTree(array $expected, array $ast): void
    {
        self::assertEquals($expected, (new Level2PrettyPrinter())->prettyPrintIntoTree($ast));
    }

    /** @return array{expected: string, ast: array<Node>} */
    public function prettyPrintIntoTreeProvider(): array
    {
        return [
            'empty' => [
                'expected' => [],
                'ast' => [],
            ],
            'foo' => [
                'expected' => [],
                'ast' => [
                    new Node\Expr\Assign(
                        new Node\Expr\Variable('bar'),
                        new Node\Scalar\LNumber(10),
                    )
                ],
            ],
//            'non empty' => [
//                'expected' => '__OLD__$bar__NEW__$x__END__ = __OLD__10__NEW__1__END__;',
//                'ast' => [
//                    new Node\Stmt\Expression(
//                        new Node\Expr\Assign(
//                            new Node\Expr\Variable('bar'),
//                            new Node\Scalar\LNumber(10),
//                        ),
//                        [
//                            'comments' => [
//                                new Comment('// foo')
//                            ]
//                        ]
//                    )
//                ],
//            ],
        ];
    }
}