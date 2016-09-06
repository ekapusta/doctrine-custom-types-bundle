<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\Postgresql;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

abstract class CubeCoordFunctionNode extends PostgresFunctionNode
{
    public $cube;
    public $int;

    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf('%s(%s, %s)', $this->getName(), $this->cube->dispatch($sqlWalker), $this->int->dispatch($sqlWalker));
    }

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->cube = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->int = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
