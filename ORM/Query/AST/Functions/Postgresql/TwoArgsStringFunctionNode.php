<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\Postgresql;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

abstract class TwoArgsStringFunctionNode extends PostgresFunctionNode
{
    public $arg1;
    public $arg2;

    public static function getReturnType()
    {
        return self::RETURN_TYPE_STRING;
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf('%s(%s, %s)', $this->getName(), $this->arg1->dispatch($sqlWalker), $this->arg2->dispatch($sqlWalker));
    }

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->arg1 = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->arg2 = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
