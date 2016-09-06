<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\ORM\Query\AST\Functions\Postgresql;

use Ekapusta\DoctrineCustomTypesBundle\Tests\ORM\Query\AST\Functions\FunctionTestCase;

class CubeFunctionTest extends FunctionTestCase
{

    public function dataForFunctionParsedAndGeneratesSqlBack()
    {
        return [
            [
                'cube(r.id)',
                'CUBE(r0_.id) AS sclr_0'
            ],
            [
                'cube(r.id, r.id)',
                'CUBE(r0_.id, r0_.id) AS sclr_0'
            ],
            [
                'cube(r.id, r.id, r.id)',
                'CUBE(r0_.id, r0_.id, r0_.id) AS sclr_0'
            ]
        ];
    }
}
