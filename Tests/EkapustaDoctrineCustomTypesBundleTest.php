<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests;

use Ekapusta\DoctrineCustomTypesBundle\EkapustaDoctrineCustomTypesBundle;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use PHPUnit\Framework\TestCase;

class EkapustaDoctrineCustomTypesBundleTest extends TestCase
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
