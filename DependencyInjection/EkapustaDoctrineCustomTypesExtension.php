<?php

namespace Ekapusta\DoctrineCustomTypesBundle\DependencyInjection;

use Ekapusta\DoctrineCustomTypesBundle\DBAL\TypeRegistry;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class EkapustaDoctrineCustomTypesExtension extends Extension implements PrependExtensionInterface
{

    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        if (!isset($bundles['DoctrineBundle'])) {
            throw new RuntimeException('Doctrine bundle required!');
        }

        $container->prependExtensionConfig('doctrine', [
            'dbal' => [
                'types' => (new TypeRegistry())->getDoctrineMapping(),
            ]
        ]);
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
