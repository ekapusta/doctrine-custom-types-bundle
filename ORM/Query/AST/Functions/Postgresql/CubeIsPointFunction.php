<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\Postgresql;

/**
 * Returns true if a cube is a point, that is, the two defining corners are the same.
 *
 * "CUBE_IS_POINT" "(" ArithmeticPrimary ")"
 */
final class CubeIsPointFunction extends PostgresFunctionNode
{
}
