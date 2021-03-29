<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\ORM\Query\AST\Functions\Postgresql;

use Ekapusta\DoctrineCustomTypesBundle\Tests\ORM\Query\AST\Functions\FunctionTestCase;

class CubeDimFunctionTest extends FunctionTestCase
{
    public function dataForFunctionParsedAndGeneratesSqlBack()
    {
        return [
            [
                'cube_dim(r.id)',
                'CUBE_DIM(r0_.id) AS sclr_0',
            ],
        ];
    }
}
