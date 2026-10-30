<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\ORM;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

abstract class OrmTestCase extends TestCase
{
    protected EntityManager $entityManager;

    protected function setUp(): void
    {
        $config = new Configuration();
        $config->setProxyDir(sys_get_temp_dir().'/DoctrineTestsProxies');
        $config->setProxyNamespace('Ekapusta\DoctrineCustomTypesBundle\Tests\Proxies');
        $config->setAutoGenerateProxyClasses(true);
        $config->setMetadataDriverImpl(new AttributeDriver([__DIR__.'/Entities']));
        $this->configure($config);
        $connection = DriverManager::getConnection(['driver' => 'pdo_sqlite', 'memory' => true], $config);
        // ORM < 2.13 has protected constructor, ORM 3 has no create()
        $this->entityManager = (new ReflectionMethod(EntityManager::class, '__construct'))->isPublic()
            ? new EntityManager($connection, $config)
            : EntityManager::create($connection, $config);
    }

    abstract protected function configure(Configuration $config);
}
