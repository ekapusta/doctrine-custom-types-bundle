<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\Postgresql;

/**
 * Returns the n'th coordinate value for the lower left corner of a cube.
 *
 * "CUBE_LL_COORD" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
 */
final class CubeLlCoordFunction extends PostgresFunctionNode
{
    protected const MIN_ARGUMENTS = 2;
    protected const MAX_ARGUMENTS = 2;
}
