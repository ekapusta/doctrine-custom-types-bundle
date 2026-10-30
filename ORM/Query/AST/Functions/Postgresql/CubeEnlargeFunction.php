<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\Postgresql;

/**
 * Increases the size of a cube by a specified radius in at least n dimensions.
 * If the radius is negative the cube is shrunk instead.
 *
 * This is useful for creating bounding boxes around a point for searching for nearby points.
 * All defined dimensions are changed by the radius r.
 * LL coordinates are decreased by r and UR coordinates are increased by r.
 *
 * If a LL coordinate is increased to larger than the corresponding UR coordinate
 * (this can only happen when r < 0) than both coordinates are set to their average.
 *
 * If n is greater than the number of defined dimensions and the cube is being increased (r >= 0)
 * then 0 is used as the base for the extra coordinates.
 *
 * "CUBE_ENLARGE" "(" ArithmeticPrimary "," ArithmeticPrimary  "," ArithmeticPrimary ")"
 */
final class CubeEnlargeFunction extends PostgresFunctionNode
{
    protected const MIN_ARGUMENTS = 3;
    protected const MAX_ARGUMENTS = 3;

    public static function getReturnType(): string
    {
        return self::RETURN_TYPE_STRING;
    }
}
