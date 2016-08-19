<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\DependencyInjection;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Ekapusta\DoctrineCustomTypesBundle\DependencyInjection\EkapustaDoctrineCustomTypesExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EkapustaDoctrineCustomTypesExtensionTest extends \PHPUnit_Framework_TestCase
{

    public function testLoaded()
    {
        $extension = new EkapustaDoctrineCustomTypesExtension();
        $container = new ContainerBuilder();
        $extension->load([], $container);

        return $container;
    }

    /**
     * @depends testLoaded
     */
    public function testColumnListenerServiceAdded(ContainerBuilder $container)
    {
        $this->assertTrue($container->hasDefinition('ekapustadoctrinecustomtypes.schemacolumn.listener'));
    }

    /**
     * @expectedException Symfony\Component\DependencyInjection\Exception\RuntimeException
     * @expectedExceptionMessage Doctrine
     */
    public function testRequiresDoctrineBundle()
    {
        $extension = new EkapustaDoctrineCustomTypesExtension();
        $container = new ContainerBuilder();
        $container->setParameter('kernel.bundles', []);
        $extension->prepend($container);
    }

    public function testPrependsDoctrineConfig()
    {
        $extension = new EkapustaDoctrineCustomTypesExtension();
        $container = new ContainerBuilder();
        $container->setParameter('kernel.bundles', ['DoctrineBundle' => new DoctrineBundle()]);
        $extension->prepend($container);

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
}
