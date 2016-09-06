<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\ORM\Query\AST\Functions\Postgresql;

use Ekapusta\DoctrineCustomTypesBundle\Tests\ORM\Query\AST\Functions\FunctionTestCase;

class CubeCoordFunctionsTest extends FunctionTestCase
{

    public function dataForFunctionParsedAndGeneratesSqlBack()
    {
        return [
            [
                'cube_ll_coord(r.id, 1), cube_ur_coord(r.id, 2)',
                'CUBE_LL_COORD(r0_.id, 1) AS sclr_0, CUBE_UR_COORD(r0_.id, 2) AS sclr_1'
            ]
        ];
    }
}
