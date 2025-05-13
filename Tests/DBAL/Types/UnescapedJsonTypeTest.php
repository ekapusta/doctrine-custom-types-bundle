<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\DBAL\Types;

use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Types\JsonType;
use Doctrine\DBAL\Types\Type;
use Ekapusta\DoctrineCustomTypesBundle\DBAL\Types\UnescapedJsonType;
use PHPUnit\Framework\TestCase;

class UnescapedJsonTypeTest extends TestCase
{
    /**
     * @var UnescapedJsonType
     */
    private $type;

    private $platform;

    protected function setUp()
    {
        if (!class_exists(JsonType::class)) {
            $this->markTestSKipped('Class JsonType not yet exists');
        }
        if (!Type::hasType('unescaped_json')) {
            Type::addType('unescaped_json', UnescapedJsonType::class);
        }

        $this->type = Type::getType('unescaped_json');
        $this->platform = new MySqlPlatform();
    }

    public function testHasName()
    {
        $this->assertEquals('unescaped_json', $this->type->getName());
    }

    public function testSqlDeclarationGenerated()
    {
        $sql = $this->type->getSQLDeclaration([], $this->platform);

        $this->assertEquals("LONGTEXT", $sql);
    }

    public function testConvert()
    {
        $data = ['data' => 'абвгд'];

        $sql = $this->type->convertToDatabaseValue($data, $this->platform);
        $this->assertEquals('{"data":"абвгд"}', $sql);

        $convertedData = $this->type->convertToPHPValue($sql, $this->platform);
        $this->assertEquals($data, $convertedData);
    }
}
