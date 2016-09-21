<?php

namespace Ekapusta\DoctrineCustomTypesBundle\DependencyInjection;

use Ekapusta\DoctrineCustomTypesBundle\DBAL\TypeRegistry;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\FunctionRegistry;

class EkapustaDoctrineCustomTypesExtension extends Extension implements PrependExtensionInterface
{

    public function prepend(ContainerBuilder $container)
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

    private function extractEntityManagers(ContainerBuilder $container)
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

    private function generateDqlConfig(ContainerBuilder $container)
    {
        $functions = new FunctionRegistry($container->getExtensionConfig('doctrine'));

        return [
            'string_functions'   => $functions->getStringFunctions(),
            'numeric_functions'  => $functions->getNumericFunctions(),
            'datetime_functions' => $functions->getDatetimeFunctions(),
        ];
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
