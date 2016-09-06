<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\Postgresql;

/**
 * Produces the union of two cubes
 *
 * "CUBE_UNION" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
 */
class CubeUnionFunction extends TwoArgsStringFunctionNode
{
}
