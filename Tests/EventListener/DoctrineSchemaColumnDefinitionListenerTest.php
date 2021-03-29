<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\EventListener;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Event\SchemaColumnDefinitionEventArgs;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Types\Type;
use Ekapusta\DoctrineCustomTypesBundle\EventListener\DoctrineSchemaColumnDefinitionListener;
use PHPUnit\Framework\TestCase;

class DoctrineSchemaColumnDefinitionListenerTest extends TestCase
{
    public function testDefaultColumnNotExtracted()
    {
        $args = $this->defineTableColumn(['Type' => 'varchar(255)']);

        $this->assertFalse($args->isDefaultPrevented());
        $this->assertNull($args->getColumn());
    }

    public function testRegisteredDefinableColumnExtracted()
    {
        Type::addType('dummy', DbalDummyType::class);
        $platform = new MySqlPlatform();
        $platform->registerDoctrineTypeMapping('dummy', 'dummy');

        $args = $this->defineTableColumn(['Type' => 'dummy(1,2,3)'], $platform);
        $this->assertTrue($args->isDefaultPrevented());
        $this->assertNotNull($args->getColumn());
        $this->assertInstanceOf(Column::class, $args->getColumn());
    }

    private function defineTableColumn(array $tableColumn, $platform = null)
    {
        $platform = $platform ?: new MySqlPlatform();
        $driver = new Driver\PDOMySql\Driver();
        $connection = new Connection(['platform' => $platform], $driver);
        $args = new SchemaColumnDefinitionEventArgs($tableColumn, 'sometable', 'somedb', $connection);
        (new DoctrineSchemaColumnDefinitionListener())->onSchemaColumnDefinition($args);

        return $args;
    }
}
