<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\Postgresql;

/**
 * Returns the n'th coordinate value for the upper right corner of a cube.
 *
 * "CUBE_UR_COORD" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
 */
class CubeUrCoordFunction extends CubeCoordFunctionNode
{
}
