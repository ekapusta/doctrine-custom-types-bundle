<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\ORM\Query\AST\Functions\Postgresql;

use Ekapusta\DoctrineCustomTypesBundle\Tests\ORM\Query\AST\Functions\FunctionTestCase;

class CubeDistanceFunctionTest extends FunctionTestCase
{

    public function dataForFunctionParsedAndGeneratesSqlBack()
    {
        return [
            [
                'cube_distance(r.id, r.name)',
                'CUBE_DISTANCE(r0_.id, r0_.name) AS sclr_0'
            ]
        ];
    }
}
