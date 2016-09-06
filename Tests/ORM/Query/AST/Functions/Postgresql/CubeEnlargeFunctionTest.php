<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\ORM\Query\AST\Functions\Postgresql;

use Ekapusta\DoctrineCustomTypesBundle\Tests\ORM\Query\AST\Functions\FunctionTestCase;

class CubeEnlargeFunctionTest extends FunctionTestCase
{

    public function dataForFunctionParsedAndGeneratesSqlBack()
    {
        return [
            [
                'cube_enlarge(r.id, 1, 2)',
                'CUBE_ENLARGE(r0_.id, 1, 2) AS sclr_0'
            ]
        ];
    }
}
