<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\DependencyInjection\Compiler;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\DoctrineExtension;
use Ekapusta\DoctrineCustomTypesBundle\DependencyInjection\Compiler\RegisterConnectionTypeMappingCompilerPass;
use Ekapusta\DoctrineCustomTypesBundle\DependencyInjection\EkapustaDoctrineCustomTypesExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterConnectionTypeMappingCompilerPassTest extends \PHPUnit_Framework_TestCase
{

    public function testDoctrineLoaded()
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.debug', false);

        (new DoctrineExtension())->load([[
            'dbal'  => [
                'connections'        => [
                    'default'   => [
                        'driver'    => 'pdo_mysql',
                    ],
                    'another' => [
                        'driver'    => 'pdo_pgsql',
                    ],
                    'last' => [
                        'driver'    => 'ibm_db2',
                    ],
                ]
            ],
        ]], $container);

        return $container;
    }

    /**
     * @depends testDoctrineLoaded
     */
    public function testConnectionServicesExists(ContainerBuilder $container)
    {
        $this->assertDefinitionExistsAndTypesAreEmpty($container, 'doctrine.dbal.default_connection');
        $this->assertDefinitionExistsAndTypesAreEmpty($container, 'doctrine.dbal.another_connection');
        $this->assertDefinitionExistsAndTypesAreEmpty($container, 'doctrine.dbal.last_connection');
    }

    /**
     * @depends testDoctrineLoaded
     */
    public function testExtensionAndCompilerPassProcessed(ContainerBuilder $container)
    {
        (new EkapustaDoctrineCustomTypesExtension())->load([], $container);
        (new RegisterConnectionTypeMappingCompilerPass())->process($container);

        return $container;
    }

    /**
     * @depends testExtensionAndCompilerPassProcessed
     */
    public function testTypesAddedWithRespectToCompatibility(ContainerBuilder $container)
    {
        $this->assertDefinitionExistsAndTypesContains($container, 'doctrine.dbal.default_connection', 'enum');
        $this->assertDefinitionExistsAndTypesContains($container, 'doctrine.dbal.another_connection', 'cube');
        $this->assertDefinitionExistsAndTypesAreEmpty($container, 'doctrine.dbal.last_connection');
    }

    private function assertDefinitionExistsAndTypesAreEmpty(ContainerBuilder $container, $id)
    {
        $this->assertTrue($container->hasDefinition($id));
        $this->assertEmpty($container->getDefinition($id)->getArgument(3));
    }

    private function assertDefinitionExistsAndTypesContains(ContainerBuilder $container, $id, $expectedType)
    {
        $this->assertTrue($container->hasDefinition($id));
        $typesArgument = $container->getDefinition($id)->getArgument(3);
        $this->assertNotEmpty($typesArgument);
        $this->assertContains($expectedType, $typesArgument);

    }
}
