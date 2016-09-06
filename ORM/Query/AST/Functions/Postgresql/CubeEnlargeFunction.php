<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\Postgresql;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Increases the size of a cube by a specified radius in at least n dimensions.
 * If the radius is negative the cube is shrunk instead.
 *
 * This is useful for creating bounding boxes around a point for searching for nearby points.
 * All defined dimensions are changed by the radius r.
 * LL coordinates are decreased by r and UR coordinates are increased by r.
 *
 * If a LL coordinate is increased to larger than the corresponding UR coordinate
 * (this can only happen when r < 0) than both coordinates are set to their average.
 *
 * If n is greater than the number of defined dimensions and the cube is being increased (r >= 0)
 * then 0 is used as the base for the extra coordinates.
 *
 * "CUBE_ENLARGE" "(" ArithmeticPrimary "," ArithmeticPrimary  "," ArithmeticPrimary ")"
 */
class CubeEnlargeFunction extends PostgresFunctionNode
{
    public $cube;
    public $radius;
    public $nDimensions;

    public static function getReturnType()
    {
        return self::RETURN_TYPE_STRING;
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf('%s(%s, %s, %s)', $this->getName(), $this->cube->dispatch($sqlWalker),
            $this->radius->dispatch($sqlWalker), $this->nDimensions->dispatch($sqlWalker));
    }

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->cube = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->radius = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->nDimensions = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
