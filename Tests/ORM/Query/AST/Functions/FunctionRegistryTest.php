<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\ORM\Query\AST\Functions;

use Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\FunctionRegistry;
use Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\Postgresql\CubeDistanceFunction;

class FunctionRegistryTest extends \PHPUnit_Framework_TestCase
{

    public function testOnEmptyConfigThereAreNoDrivers()
    {
        $registry = new FunctionRegistry([]);

        $this->assertAttributeEquals('', 'knownDrivers', $registry);
    }

    public function testDriversAreExtractedFromNormalConfig()
    {
        $configs = [];
        $configs[0]['dbal']['driver'] = 'pdo_mysql';
        $configs[1]['dbal']['connections']['default']['driver']  = 'pdo_mysql';
        $configs[1]['dbal']['connections']['postgres']['driver'] = 'pdo_pgsql';

        $registry = new FunctionRegistry($configs);

        $this->assertAttributeEquals('pdo_mysql pdo_pgsql', 'knownDrivers', $registry);
    }

    public function testNumericFunctionsAreLoadedOnUnknownDrivers()
    {
        $registry = new FunctionRegistry([]);

        $functions = $registry->getNumericFunctions();

        $this->assertNotEmpty($functions);
        $this->assertArrayHasKey(CubeDistanceFunction::getName(), $functions);
    }

    public function testPostgresFunctionIsLoadedWithPostgresDriver()
    {
        $configs = [];
        $configs[0]['dbal']['driver'] = 'pdo_pgsql';
        $registry = new FunctionRegistry($configs);

        $functions = $registry->getNumericFunctions();

        $this->assertNotEmpty($functions);
        $this->assertArrayHasKey(CubeDistanceFunction::getName(), $functions);
    }

    public function testPostgresFunctionIsNotLoadedWithoutPostgresDriver()
    {
        $configs = [];
        $configs[0]['dbal']['driver'] = 'pdo_mysql';
        $registry = new FunctionRegistry($configs);

        $functions = $registry->getNumericFunctions();

        $this->assertArrayNotHasKey(CubeDistanceFunction::getName(), $functions);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Classname Wtf\Wow not exists under
     */
    public function testNotExistedClassThrowsException()
    {
        $empty = tempnam(sys_get_temp_dir(), 'SomeFunction');
        file_put_contents($empty, '<?php namespace Wtf; class Wow {}');

        $registry = new FunctionRegistry([], $empty);
        $registry->getNumericFunctions();
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Classname  not exists under
     */
    public function testNotExistedEmptyClassThrowsException()
    {
        $empty = tempnam(sys_get_temp_dir(), 'SomeFunction');

        $registry = new FunctionRegistry([], $empty);
        $registry->getNumericFunctions();
    }
}
