<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests;

use Ekapusta\DoctrineCustomTypesBundle\EkapustaDoctrineCustomTypesBundle;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EkapustaDoctrineCustomTypesBundleTest extends \PHPUnit_Framework_TestCase
{

    public function testBuild()
    {
        $container = $this->getMockBuilder(ContainerBuilder::class)
            ->setMethods(['addCompilerPass'])
            ->getMock();
        $container->expects($this->atLeastOnce())
            ->method('addCompilerPass')
            ->with($this->isInstanceOf(CompilerPassInterface::class));

        $bundle = new EkapustaDoctrineCustomTypesBundle();
        $bundle->build($container);
    }
}
