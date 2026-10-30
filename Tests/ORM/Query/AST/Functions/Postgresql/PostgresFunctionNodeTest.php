<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\ORM\Query\AST\Functions\Postgresql;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\Query\QueryException;
use Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\FunctionRegistry;
use Ekapusta\DoctrineCustomTypesBundle\Tests\ORM\OrmTestCase;

class PostgresFunctionNodeTest extends OrmTestCase
{
    protected function configure(Configuration $config)
    {
        $functions = new FunctionRegistry([]);
        $config->setCustomNumericFunctions($functions->getNumericFunctions());
        $config->setCustomStringFunctions($functions->getStringFunctions());
    }

    /**
     * @dataProvider dataForWrongArgumentsCount
     */
    public function testWrongArgumentsCountIsSyntaxError($dqlSelectPart)
    {
        $this->expectException(QueryException::class);

        $dql = sprintf('SELECT %s FROM Ekapusta\DoctrineCustomTypesBundle\Tests\ORM\Entities\Reference AS r', $dqlSelectPart);
        $this->entityManager->createQuery($dql)->getSql();
    }

    public static function dataForWrongArgumentsCount()
    {
        return [
            'too few' => ['cube_distance(r.id)'],
            'too many' => ['cube_dim(r.id, r.id)'],
            'too many optional' => ['cube(r.id, r.id, r.id, r.id)'],
        ];
    }
}
