<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests;

use Ekapusta\DoctrineCustomTypesBundle\DependencyInjection\Compiler\RegisterConnectionTypeMappingCompilerPass;
use Ekapusta\DoctrineCustomTypesBundle\EkapustaDoctrineCustomTypesBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EkapustaDoctrineCustomTypesBundleTest extends TestCase
{
    public function testBuild()
    {
        $container = new ContainerBuilder();

        $bundle = new EkapustaDoctrineCustomTypesBundle();
        $bundle->build($container);

        $passes = array_map('get_class', $container->getCompilerPassConfig()->getPasses());

        $this->assertContains(RegisterConnectionTypeMappingCompilerPass::class, $passes);
    }
}
