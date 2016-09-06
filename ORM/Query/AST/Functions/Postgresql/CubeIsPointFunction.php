<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\Postgresql;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Returns true if a cube is a point, that is, the two defining corners are the same.
 *
 * "CUBE_IS_POINT" "(" ArithmeticPrimary ")"
 */
class CubeIsPointFunction extends PostgresFunctionNode
{
    public $cube;

    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf('%s(%s)', $this->getName(), $this->cube->dispatch($sqlWalker));
    }

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->cube = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
