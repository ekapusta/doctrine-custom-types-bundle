<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\ORM;

use PHPUnit\Framework\TestCase;

abstract class OrmTestCase extends TestCase
{
    protected $entityManager;

    protected function setUp()
    {
        $config = new \Doctrine\ORM\Configuration();
        $config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache());
        $config->setQueryCacheImpl(new \Doctrine\Common\Cache\ArrayCache());
        $config->setProxyDir(sys_get_temp_dir().'/DoctrineTestsProxies');
        $config->setProxyNamespace('Ekapusta\DoctrineCustomTypesBundle\Tests\Proxies');
        $config->setAutoGenerateProxyClasses(true);
        $config->setMetadataDriverImpl($config->newDefaultAnnotationDriver(__DIR__.'/Entities'));
        $this->configure($config);
        $connection = ['driver' => 'pdo_sqlite', 'memory' => true];
        $this->entityManager = \Doctrine\ORM\EntityManager::create($connection, $config);
    }

    abstract protected function configure(\Doctrine\ORM\Configuration $config);
}
