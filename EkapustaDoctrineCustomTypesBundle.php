<?php

namespace Ekapusta\DoctrineCustomTypesBundle;

use Ekapusta\DoctrineCustomTypesBundle\DependencyInjection\Compiler\RegisterConnectionTypeMappingCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EkapustaDoctrineCustomTypesBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterConnectionTypeMappingCompilerPass());
    }
}
