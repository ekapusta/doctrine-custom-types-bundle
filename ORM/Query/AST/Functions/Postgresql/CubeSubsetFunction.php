<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\Postgresql;

/**
 * Makes a new cube from an existing cube, using a list of dimension indexes from an array.
 *
 * Can be used to find both the LL and UR coordinates of a single dimension,
 * e.g. cube_subset(cube('(1,3,5),(6,7,8)'), ARRAY[2]) = '(3),(7)'.
 *
 * Or can be used to drop dimensions, or reorder them as desired,
 * e.g. cube_subset(cube('(1,3,5),(6,7,8)'), ARRAY[3,2,1,1]) = '(5, 3, 1, 1),(8, 7, 6, 6)'.
 *
 * "CUBE_SUBSET" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
 */
class CubeSubsetFunction extends TwoArgsStringFunctionNode
{
}
