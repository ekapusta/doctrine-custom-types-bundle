<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\ORM\Query\AST\Functions\Postgresql;

use Ekapusta\DoctrineCustomTypesBundle\Tests\ORM\Query\AST\Functions\FunctionTestCase;

class CubeIsPointFunctionTest extends FunctionTestCase
{
    public function dataForFunctionParsedAndGeneratesSqlBack()
    {
        return [
            [
                'cube_is_point(r.id)',
                'CUBE_IS_POINT(r0_.id) AS sclr_0',
            ],
        ];
    }
}
