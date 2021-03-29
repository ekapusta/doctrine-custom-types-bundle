<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\ORM\Query\AST\Functions;

use Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\FunctionRegistry;
use Ekapusta\DoctrineCustomTypesBundle\Tests\ORM\OrmTestCase;

/**
 * Tests concrete function(-s) parse and SQL generation.
 */
abstract class FunctionTestCase extends OrmTestCase
{
    protected function configure(\Doctrine\ORM\Configuration $config)
    {
        $functions = new FunctionRegistry([]);
        $config->setCustomNumericFunctions($functions->getNumericFunctions());
        $config->setCustomStringFunctions($functions->getStringFunctions());
        $config->setCustomDatetimeFunctions($functions->getDatetimeFunctions());
    }

    /**
     * @dataProvider dataForFunctionParsedAndGeneratesSqlBack
     */
    public function testFunctionParsedAndGeneratesSqlBack($dqlSelectPart, $sqlSelectPart)
    {
        $dql = 'SELECT %s FROM Ekapusta\DoctrineCustomTypesBundle\Tests\ORM\Entities\Reference AS r';
        $dql = sprintf($dql, $dqlSelectPart);
        $query = $this->entityManager->createQuery($dql);

        $sql = 'SELECT %s FROM Reference r0_';
        $sql = sprintf($sql, $sqlSelectPart);
        $this->assertEquals($sql, $query->getSql());
    }

    /**
     * @return array [[$dql, $sql]]
     */
    abstract public function dataForFunctionParsedAndGeneratesSqlBack();
}
