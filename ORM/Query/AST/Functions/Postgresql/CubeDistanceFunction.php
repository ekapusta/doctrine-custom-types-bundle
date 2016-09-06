<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\Postgresql;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Returns the distance between two cubes. If both cubes are points, this is the normal distance function.
 *
 * "CUBE_DISTANCE" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
 */
class CubeDistanceFunction extends PostgresFunctionNode
{
    public $cube1;
    public $cube2;

    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf('%s(%s, %s)', $this->getName(), $this->cube1->dispatch($sqlWalker), $this->cube2->dispatch($sqlWalker));
    }

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->cube1 = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->cube2 = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
