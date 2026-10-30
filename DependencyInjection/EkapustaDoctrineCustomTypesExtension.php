<?php

namespace Ekapusta\DoctrineCustomTypesBundle\DependencyInjection;

use Doctrine\DBAL\Events;
use Ekapusta\DoctrineCustomTypesBundle\DBAL\TypeRegistry;
use Ekapusta\DoctrineCustomTypesBundle\EventListener\DoctrineSchemaColumnDefinitionListener;
use Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\FunctionRegistry;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

class EkapustaDoctrineCustomTypesExtension extends Extension implements PrependExtensionInterface
{
    public const SCHEMA_COLUMN_LISTENER_ID = 'ekapustadoctrinecustomtypes.schemacolumn.listener';

    public function prepend(ContainerBuilder $container): void
    {
        $bundles = $container->getParameter('kernel.bundles');
        if (!isset($bundles['DoctrineBundle'])) {
            throw new RuntimeException('Doctrine bundle required!');
        }

        $config = [
            'dbal' => [
                'types' => (new TypeRegistry())->getDoctrineMapping(),
            ],
        ];

        $dqlConfig = $this->generateDqlConfig($container);
        foreach ($this->extractEntityManagers($container) as $manager) {
            $config['orm']['entity_managers'][$manager]['dql'] = $dqlConfig;
        }

        $container->prependExtensionConfig('doctrine', $config);
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $container->register(self::SCHEMA_COLUMN_LISTENER_ID, DoctrineSchemaColumnDefinitionListener::class)
            ->setPublic(false)
            ->addTag('doctrine.event_listener', ['event' => Events::onSchemaColumnDefinition]);
    }

    /**
     * @return list<string>
     */
    private function extractEntityManagers(ContainerBuilder $container): array
    {
        $managers = ['default' => null];

        foreach ($container->getExtensionConfig('doctrine') as $doctrineConfig) {
            if (!isset($doctrineConfig['orm']['entity_managers'])) {
                continue;
            }
            $managers += $doctrineConfig['orm']['entity_managers'];
        }

        return array_keys($managers);
    }

    private function generateDqlConfig(ContainerBuilder $container): array
    {
        $functions = new FunctionRegistry($container->getExtensionConfig('doctrine'));

        return [
            'string_functions' => $functions->getStringFunctions(),
            'numeric_functions' => $functions->getNumericFunctions(),
            'datetime_functions' => $functions->getDatetimeFunctions(),
        ];
    }
}
