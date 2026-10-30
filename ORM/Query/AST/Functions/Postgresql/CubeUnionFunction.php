<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\Postgresql;

/**
 * Produces the union of two cubes.
 *
 * "CUBE_UNION" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
 */
final class CubeUnionFunction extends PostgresFunctionNode
{
    protected const MIN_ARGUMENTS = 2;
    protected const MAX_ARGUMENTS = 2;

    public static function getReturnType(): string
    {
        return self::RETURN_TYPE_STRING;
    }
}
