<?php
declare(strict_types=1);

namespace App\PrettyPrinter;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\PrettyPrinter\Standard;

class Level1PrettyPrinter extends Standard
{
    protected $nl = ' ';

    /** @param Node[] $nodes */
    protected function pStmts(array $nodes, bool $indent = true): string
    {
        $result = '';
        foreach ($nodes as $node) {
            if ($node instanceof Stmt\Nop) {
                continue;
            }

            $result .= $this->nl . $this->p($node);
        }

        return $result;
    }

    protected function indent(): void
    {
    }

    protected function outdent(): void
    {
    }

    protected function resetState(): void
    {
        parent::resetState();
        $this->nl = ' ';
    }
}
