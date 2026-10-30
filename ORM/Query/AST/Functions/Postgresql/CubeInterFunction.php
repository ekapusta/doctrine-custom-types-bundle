<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\Postgresql;

/**
 * Produces the intersection of two cubes.
 *
 * "CUBE_INTER" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
 */
final class CubeInterFunction extends PostgresFunctionNode
{
    protected const MIN_ARGUMENTS = 2;
    protected const MAX_ARGUMENTS = 2;

    public static function getReturnType(): string
    {
        return self::RETURN_TYPE_STRING;
    }
}
