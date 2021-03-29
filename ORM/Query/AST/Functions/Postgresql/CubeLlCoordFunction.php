<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\Postgresql;

/**
 * Returns the n'th coordinate value for the lower left corner of a cube.
 *
 * "CUBE_LL_COORD" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
 */
class CubeLlCoordFunction extends CubeCoordFunctionNode
{
}
