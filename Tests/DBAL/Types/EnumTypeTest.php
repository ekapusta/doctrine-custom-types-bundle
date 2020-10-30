<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\DBAL\Types;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Types\Type;
use Ekapusta\DoctrineCustomTypesBundle\DBAL\Types\EnumType;
use PHPUnit\Framework\TestCase;
use Doctrine\DBAL\Exception\InvalidArgumentException;

class EnumTypeTest extends TestCase
{

    /**
     * @var EnumType
     */
    private $type;

    private $platform;

    protected function setUp(): void
    {
        if (!Type::hasType('enum')) {
            Type::addType('enum', EnumType::class);
        }

        $this->type = Type::getType('enum');
        $this->platform = new MySqlPlatform();
    }

    public function testHasName()
    {
        $this->assertEquals('enum', $this->type->getName());
    }

    /**
     * @dataProvider dataForSqlDeclarationRequiresValues
     */
    public function testSqlDeclarationRequiresValues($fieldDeclaration)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('required');

        $this->type->getSQLDeclaration($fieldDeclaration, $this->platform);
    }

    public function dataForSqlDeclarationRequiresValues()
    {
        return [
            [[]],
            [['values' => []]]
        ];
    }

    public function testSqlDeclarationGenerated()
    {
        $sql = $this->type->getSQLDeclaration(['values' => [1, 'two', 3]], $this->platform);

        $this->assertEquals("enum('1','two','3')", $sql);
    }

    public function testColumnDefinitionExtracted()
    {
        $column = $this->type->getColumnDefinition([
            'field'     => 'status',
            'default'   => 'start',
            'comment'   => 'Some status field',
            'null'      => 'NO',
            'type'      => "enum('start', 'process', 'finish')",
        ], $this->platform);

        $this->assertNotNull($column);

        $this->assertEquals('status', $column->getName());
        $this->assertEquals('start', $column->getDefault());
        $this->assertEquals('Some status field', $column->getComment());
        $this->assertTrue($column->getNotnull());

        $this->assertArrayHasKey('values', $column->getCustomSchemaOptions());
        $this->assertEquals(['start', 'process', 'finish'], $column->getCustomSchemaOption('values'));
    }
}
