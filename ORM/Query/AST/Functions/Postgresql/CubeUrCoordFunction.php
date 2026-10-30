<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\Postgresql;

/**
 * Returns the n'th coordinate value for the upper right corner of a cube.
 *
 * "CUBE_UR_COORD" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
 */
final class CubeUrCoordFunction extends PostgresFunctionNode
{
    protected const MIN_ARGUMENTS = 2;
    protected const MAX_ARGUMENTS = 2;
}
