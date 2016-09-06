<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\ORM\Query\AST\Functions\Postgresql;


use Ekapusta\DoctrineCustomTypesBundle\Tests\ORM\Query\AST\Functions\FunctionTestCase;

class TwoArgsStringFunctionNodeTest extends FunctionTestCase
{

    public function dataForFunctionParsedAndGeneratesSqlBack()
    {
        return [
            [
                'cube_subset(r.id, r.id), cube_union(r.id, r.id), cube_inter(r.id, r.id)',
                'CUBE_SUBSET(r0_.id, r0_.id) AS sclr_0, CUBE_UNION(r0_.id, r0_.id) AS sclr_1, CUBE_INTER(r0_.id, r0_.id) AS sclr_2'
            ]
        ];
    }
}
