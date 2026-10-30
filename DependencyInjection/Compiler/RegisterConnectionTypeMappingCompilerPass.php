<?php

namespace Ekapusta\DoctrineCustomTypesBundle\DependencyInjection\Compiler;

use Ekapusta\DoctrineCustomTypesBundle\DBAL\TypeRegistry;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterConnectionTypeMappingCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $registry = new TypeRegistry();
        foreach ($container->getDefinitions() as $definition) {
            if ($definition instanceof ChildDefinition && $definition->getParent() === 'doctrine.dbal.connection') {
                $this->addTypeMapping($definition, $registry);
            }
        }
    }

    private function addTypeMapping(ChildDefinition $connectionDefinition, TypeRegistry $registry): void
    {
        $arguments = $connectionDefinition->getArguments();
        $driver = $connectionDefinition->getArgument(0)['driver'];
        $arguments[3] += $registry->getDatabaseMapping($driver);
        $connectionDefinition->setArguments($arguments);
    }
}
