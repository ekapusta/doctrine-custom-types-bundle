<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\Postgresql;

/**
 * Returns the distance between two cubes. If both cubes are points, this is the normal distance function.
 *
 * "CUBE_DISTANCE" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
 */
final class CubeDistanceFunction extends PostgresFunctionNode
{
    protected const MIN_ARGUMENTS = 2;
    protected const MAX_ARGUMENTS = 2;
}
