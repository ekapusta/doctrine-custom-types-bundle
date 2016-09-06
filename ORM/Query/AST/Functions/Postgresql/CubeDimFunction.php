<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\Postgresql;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Returns the number of dimensions of the cube
 *
 * "CUBE_DIM" "(" ArithmeticPrimary ")"
 */
class CubeDimFunction extends PostgresFunctionNode
{
    public $cube1;

    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf('%s(%s)', $this->getName(), $this->cube1->dispatch($sqlWalker));
    }

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->cube1 = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
