<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\Postgresql;

/**
 * Produces the intersection of two cubes.
 *
 * "CUBE_INTER" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
 */
class CubeInterFunction extends TwoArgsStringFunctionNode
{
}
