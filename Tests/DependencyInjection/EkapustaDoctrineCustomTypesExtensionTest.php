<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\DependencyInjection;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\DoctrineExtension;
use Ekapusta\DoctrineCustomTypesBundle\DependencyInjection\EkapustaDoctrineCustomTypesExtension;
use Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\Postgresql\CubeDistanceFunction;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

class EkapustaDoctrineCustomTypesExtensionTest extends TestCase
{

    public function testLoaded()
    {
        $extension = new EkapustaDoctrineCustomTypesExtension();
        $container = new ContainerBuilder();
        $extension->load([], $container);

        $this->assertInstanceOf(ContainerBuilder::class, $container);

        return $container;
    }

    /**
     * @depends testLoaded
     */
    public function testColumnListenerServiceAdded(ContainerBuilder $container)
    {
        $this->assertTrue($container->hasDefinition('ekapustadoctrinecustomtypes.schemacolumn.listener'));
    }

    public function testRequiresDoctrineBundle()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Doctrine');

        $extension = new EkapustaDoctrineCustomTypesExtension();
        $container = new ContainerBuilder();
        $container->setParameter('kernel.bundles', []);
        $extension->prepend($container);

        $this->assertInstanceOf(ContainerBuilder::class, $container);
    }

    public function testPrependsDoctrineConfig()
    {
        $extension = new EkapustaDoctrineCustomTypesExtension();
        $container = new ContainerBuilder();
        $container->setParameter('kernel.bundles', ['DoctrineBundle' => new DoctrineBundle()]);
        $extension->prepend($container);

        $this->assertInstanceOf(ContainerBuilder::class, $container);

        return $container;
    }

    /**
     * @depends testPrependsDoctrineConfig
     */
    public function testPrependedConfigHasDbalTypes(ContainerBuilder $container)
    {
        $config = $container->getExtensionConfig('doctrine');

        $this->assertNotEmpty($config);

        $this->assertArrayHasKey('dbal', $config[0]);
        $this->assertArrayHasKey('types', $config[0]['dbal']);
    }

    /**
     * @depends testPrependsDoctrineConfig
     */
    public function testPrependedConfigHasOrmNumericFunctions(ContainerBuilder $container)
    {
        $config = $container->getExtensionConfig('doctrine');

        $this->assertNotEmpty($config);

        $this->assertTrue(isset($config[0]['orm']['entity_managers']['default']['dql']['numeric_functions']));
    }

    /**
     * @dataProvider dataForDoctrineHasFunctionsAtEntityManager
     */
    public function testDoctrineHasFunctionsAtEntityManager($doctrineOrmConfigId, $doctrineConfig)
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.name', 'Qwerty');
        $container->setParameter('kernel.debug', false);
        $container->setParameter('kernel.root_dir', __DIR__);
        $container->setParameter('kernel.environment', 'prod');
        $container->setParameter('kernel.bundles', ['DoctrineBundle' => new DoctrineBundle()]);
        $doctrine = new DoctrineExtension();
        $container->registerExtension($doctrine);

        $container->loadFromExtension('doctrine', $doctrineConfig);

        $extension = new EkapustaDoctrineCustomTypesExtension();
        $extension->prepend($container);

        $doctrine->load($container->getExtensionConfig('doctrine'), $container);

        $this->assertContains([
            'addCustomNumericFunction',
            ['CUBE_DISTANCE', CubeDistanceFunction::class]
        ], $container->getDefinition($doctrineOrmConfigId)->getMethodCalls());
    }

    public function dataForDoctrineHasFunctionsAtEntityManager()
    {
        return [
            ['doctrine.orm.default_configuration', [
                'orm' => [
                    'auto_mapping' => true,
                ]
            ]],
            ['doctrine.orm.default_configuration', [
                'orm' => [
                    'entity_managers' => [
                        'default' => [
                        ],
                        'another' => [
                        ],
                    ],
                ]
            ]],
            ['doctrine.orm.another_configuration', [
                'orm' => [
                    'entity_managers' => [
                        'default' => [
                        ],
                        'another' => [
                        ],
                    ],
                ]
            ]],
        ];
    }
}
