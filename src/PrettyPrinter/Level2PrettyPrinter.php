<?php
declare(strict_types=1);

namespace App\PrettyPrinter;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Scalar;
use PhpParser\Node\Stmt;

class Level2PrettyPrinter extends Level1PrettyPrinter
{
    private const CONST_REPLACEMENT = 'con';
    private const IDENTIFIER_REPLACEMENT = 'identifier';
    private const VAR_LIKE_IDENTIFIER_REPLACEMENT = '$x';
    private const STRING_REPLACEMENT = "'str'";
    private const L_NUMBER_REPLACEMENT = '1';
    private const D_NUMBER_REPLACEMENT = '1.0';
    private const EMPTY = '';
    private const INTERFACE_REPLACEMENT = 'interf';
    private const ENUM_REPLACEMENT = 'enu';
    private const CLASS_REPLACEMENT = 'Cl';
    private const PROPERTY_REPLACEMENT = '$prop';
    private const FUNCTION_REPLACEMENT = 'fun';
    private const CALL_LIKE_REPLACEMENT = 'funcall';

    protected function pArg(Node\Arg $node): string
    {
        return ($node->name ? 'arg' . ': ' : '')
            . ($node->byRef ? '&' : '') . ($node->unpack ? '...' : '')
            . $this->p($node->value);
    }

    protected function pConst(Node\Const_ $node): string
    {
        return self::CONST_REPLACEMENT . ' = ' . $this->p($node->value);
    }

    protected function pIdentifier(Node\Identifier $node): string
    {
        return self::IDENTIFIER_REPLACEMENT;
    }

    protected function pVarLikeIdentifier(Node\VarLikeIdentifier $node): string
    {
        return self::VAR_LIKE_IDENTIFIER_REPLACEMENT;
    }

    protected function pScalar_String(Scalar\String_ $node): string
    {
        return self::STRING_REPLACEMENT;
    }

    protected function pScalar_LNumber(Scalar\LNumber $node): string
    {
        return self::L_NUMBER_REPLACEMENT;
    }

    protected function pScalar_DNumber(Scalar\DNumber $node): string
    {
        return self::D_NUMBER_REPLACEMENT;
    }

    protected function pExpr_Variable(Expr\Variable $node): string
    {
        if ($node->name instanceof Expr) {
            return '${' . $this->p($node->name) . '}';
        }

        return self::VAR_LIKE_IDENTIFIER_REPLACEMENT;
    }

    protected function pExpr_ConstFetch(Expr\ConstFetch $node): string
    {
        return $this->p($node->name);
    }

    protected function pStmt_Namespace(Stmt\Namespace_ $node): string
    {
        return self::EMPTY;
    }

    protected function pStmt_Use(Stmt\Use_ $node): string
    {
        return self::EMPTY;
    }

    protected function pStmt_GroupUse(Stmt\GroupUse $node): string
    {
        return self::EMPTY;
    }

    protected function pStmt_UseUse(Stmt\UseUse $node): string
    {
        return self::EMPTY;
    }

    protected function pStmt_Declare(Stmt\Declare_ $node): string
    {
        return self::EMPTY;
    }

    protected function pStmt_Interface(Stmt\Interface_ $node): string
    {
        return $this->pAttrGroups($node->attrGroups)
            . 'interface ' . self::INTERFACE_REPLACEMENT
            . (!empty($node->extends) ? ' extends ' . $this->pCommaSeparated($node->extends) : '')
            . $this->nl . '{' . $this->pStmts($node->stmts) . $this->nl . '}';
    }

    protected function pStmt_Enum(Stmt\Enum_ $node): string
    {
        return $this->pAttrGroups($node->attrGroups)
            . 'enum ' . self::ENUM_REPLACEMENT
            . ($node->scalarType ? " : $node->scalarType" : '')
            . (!empty($node->implements) ? ' implements ' . $this->pCommaSeparated($node->implements) : '')
            . $this->nl . '{' . $this->pStmts($node->stmts) . $this->nl . '}';
    }

    /** @param string $afterClassToken */
    protected function pClassCommon(Stmt\Class_ $node, $afterClassToken): string
    {
        return $this->pAttrGroups($node->attrGroups, $node->name === null)
            . $this->pModifiers($node->flags)
            . 'class ' . self::CLASS_REPLACEMENT
            . (null !== $node->extends ? ' extends ' . $this->p($node->extends) : '')
            . (!empty($node->implements) ? ' implements ' . $this->pCommaSeparated($node->implements) : '')
            . $this->nl . '{' . $this->pStmts($node->stmts) . $this->nl . '}';
    }

    protected function pStmt_PropertyProperty(Stmt\PropertyProperty $node): string
    {
        return self::PROPERTY_REPLACEMENT
            . (null !== $node->default ? ' = ' . $this->p($node->default) : '');
    }

    protected function pStmt_ClassMethod(Stmt\ClassMethod $node): string
    {
        return $this->pAttrGroups($node->attrGroups)
            . $this->pModifiers($node->flags)
            . 'function ' . ($node->byRef ? '&' : '') . self::FUNCTION_REPLACEMENT
            . '(' . $this->pMaybeMultiline($node->params) . ')'
            . (null !== $node->returnType ? ' : ' . $this->p($node->returnType) : '')
            . (null !== $node->stmts
                ? $this->nl . '{' . $this->pStmts($node->stmts) . $this->nl . '}'
                : ';');
    }

    protected function pStmt_Function(Stmt\Function_ $node): string
    {
        return $this->pAttrGroups($node->attrGroups)
            . 'function ' . ($node->byRef ? '&' : '') . self::FUNCTION_REPLACEMENT
            . '(' . $this->pCommaSeparated($node->params) . ')'
            . (null !== $node->returnType ? ' : ' . $this->p($node->returnType) : '')
            . $this->nl . '{' . $this->pStmts($node->stmts) . $this->nl . '}';
    }

    protected function pExpr_FuncCall(Expr\FuncCall $node): string
    {
        $name = new Node\Name([self::CALL_LIKE_REPLACEMENT], $node->getAttributes());

        return $this->pCallLhs($name)
            . '(' . $this->pMaybeMultiline($node->args) . ')';
    }

    protected function pExpr_MethodCall(Expr\MethodCall $node): string
    {
        return $this->pDereferenceLhs($node->var) . '->' . self::CALL_LIKE_REPLACEMENT
            . '(' . $this->pMaybeMultiline($node->args) . ')';
    }

    protected function pExpr_New(Expr\New_ $node): string
    {
        if ($node->class instanceof Stmt\Class_) {
            return parent::pExpr_New($node);
        }

        return 'new ' . self::CLASS_REPLACEMENT
            . '(' . $this->pMaybeMultiline($node->args) . ')';
    }
}
