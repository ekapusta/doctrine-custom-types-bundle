<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\ORM;

use PHPUnit\Framework\TestCase;

abstract class OrmTestCase extends TestCase
{
    protected $entityManager;

    protected function setUp(): void
    {
        $config = new \Doctrine\ORM\Configuration();
        $config->setProxyDir(sys_get_temp_dir().'/DoctrineTestsProxies');
        $config->setProxyNamespace('Ekapusta\DoctrineCustomTypesBundle\Tests\Proxies');
        $config->setAutoGenerateProxyClasses(true);
        $config->setMetadataDriverImpl($config->newDefaultAnnotationDriver(__DIR__.'/Entities', false));
        $this->configure($config);
        $connection = ['driver' => 'pdo_sqlite', 'memory' => true];
        error_reporting(E_ALL & ~E_WARNING);
        $this->entityManager = \Doctrine\ORM\EntityManager::create($connection, $config);
        error_reporting(E_ALL);
    }

    abstract protected function configure(\Doctrine\ORM\Configuration $config);
}
