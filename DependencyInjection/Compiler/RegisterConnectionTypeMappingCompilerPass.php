<?php

namespace Ekapusta\DoctrineCustomTypesBundle\DependencyInjection\Compiler;

use Ekapusta\DoctrineCustomTypesBundle\DBAL\TypeRegistry;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

class RegisterConnectionTypeMappingCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        foreach ($container->getDefinitions() as $definition) {
            $this->visitDefinition($definition);
        }
    }

    private function visitDefinition(Definition $definition)
    {
        if (!$definition instanceof DefinitionDecorator && !$definition instanceof ChildDefinition) {
            return;
        }
        if ($definition->getParent() != 'doctrine.dbal.connection') {
            return;
        }

        $this->addTypeMapping($definition);
    }

    private function addTypeMapping(Definition $connectionDefinition)
    {
        $arguments = $connectionDefinition->getArguments();
        $driver = $connectionDefinition->getArgument(0)['driver'];
        $arguments[3] += (new TypeRegistry())->getDatabaseMapping($driver);
        $connectionDefinition->setArguments($arguments);
    }
}
