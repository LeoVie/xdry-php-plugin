<?php

declare(strict_types=1);

namespace App\Service;

use LeoVie\PhpTokenNormalize\Model\TokenSequence;
use LeoVie\PhpTokenNormalize\Service\TokenSequenceNormalizer;
use PhpParser\Node;
use PhpParser\Node\Stmt\Nop;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;

class Level2NormalizeService
{
    public function __construct(
        private TokenSequenceNormalizer $tokenSequenceNormalizer,
        private ParserFactory           $parserFactory,
        private NodeTraverser           $nodeTraverser,
        private Standard                $prettyPrinter,
    )
    {
    }

    public function normalizeFileContent(string $fileContent): string
    {
        $normalizedByTokenization = $this->normalizeByTokenization($fileContent);
        $normalizedByParsing = $this->normalizeByParsing($normalizedByTokenization);

        return $this->normalizeByTokenization($normalizedByParsing);
    }

    private function normalizeByTokenization(string $fileContent): string
    {
        $tokenSequence = TokenSequence::create(\PhpToken::tokenize($fileContent));

        $normalizedTokenSequence = $this->tokenSequenceNormalizer->normalizeLevel2($tokenSequence);

        return $normalizedTokenSequence->toCode();
    }

    private function normalizeByParsing(string $fileContent): string
    {
        $parser = $this->parserFactory->create(ParserFactory::PREFER_PHP5);
        /** @var array<Node> $ast */
        $ast = $parser->parse('<?php ' . $fileContent);

        $this->nodeTraverser->addVisitor(new class extends NodeVisitorAbstract {
            public function enterNode(Node $node)
            {
                return NodeTraverser::DONT_TRAVERSE_CHILDREN;
            }

            public function leaveNode(Node $node): Node
            {
                if ($node instanceof Node\Stmt\Declare_) {
                    return new Nop();
                }
                return $node;
            }
        });

        $ast = $this->nodeTraverser->traverse($ast);

        return $this->prettyPrinter->prettyPrintFile($ast);
    }
}