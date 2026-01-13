<?php

namespace DBAL\Types;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Types\Type;
use Ekapusta\DoctrineCustomTypesBundle\DBAL\Types\BinaryHashType;
use PHPUnit\Framework\TestCase;

use function bin2hex;
use function hash_hmac;
use function hex2bin;

final class MysqlBinaryHashTypeTest extends TestCase
{
    /** @var BinaryHashType */
    private $type;

    /** @var MySqlPlatform */
    private $platform;

    protected function setUp()
    {
        $typeName = BinaryHashType::TYPE_NAME;

        if (!Type::hasType($typeName)) {
            Type::addType($typeName, BinaryHashType::class);
        }

        $this->type = Type::getType($typeName);
        $this->platform = new MySqlPlatform();
    }

    public function testName()
    {
        $this->assertEquals('binary_hash', $this->type->getName());
    }

    public function testSqlDeclarationGenerated()
    {
        $sql = $this->type->getSQLDeclaration(['length' => 128], $this->platform);
        $this->assertEquals('VARBINARY(128)', $sql);
    }

    /**
     * @dataProvider dataForConvertToPHPValue
     */
    public function testConvertToPHPValueSuccessfully($databaseValue, $expectedPhpValue)
    {
        $this->assertEquals($expectedPhpValue, $this->type->convertToPHPValue($databaseValue, $this->platform));
    }

    public static function dataForConvertToPHPValue()
    {
        $hash = hash_hmac('sha512', 'QWERTY', 'qwerty');

        return [
            ['', ''],
            [$hash, bin2hex($hash)],
        ];
    }

    /**
     * @dataProvider dataForConvertToDatabaseValue
     */
    public function testConvertToDatabaseValueSuccessfully($expectedPhpValue, $databaseValue)
    {
        $this->assertEquals($databaseValue, $this->type->convertToDatabaseValue($expectedPhpValue, $this->platform));
    }

    public static function dataForConvertToDatabaseValue()
    {
        $hash = hash_hmac('sha512', 'QWERTY', 'qwerty');

        return [
            ['', ''],
            [$hash, hex2bin($hash)],
        ];
    }
}
