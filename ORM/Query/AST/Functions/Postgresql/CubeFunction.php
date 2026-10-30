<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\Postgresql;

/**
 * Makes a new cube.
 *
 * "CUBE" "(" ArithmeticPrimary ")"
 * "CUBE" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
 * "CUBE" "(" ArithmeticPrimary "," ArithmeticPrimary "," ArithmeticPrimary ")"
 */
final class CubeFunction extends PostgresFunctionNode
{
    protected const MAX_ARGUMENTS = 3;

    public static function getReturnType(): string
    {
        return self::RETURN_TYPE_STRING;
    }
}
