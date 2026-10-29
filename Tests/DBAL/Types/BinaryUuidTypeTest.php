<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\DBAL\Types;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Ekapusta\DoctrineCustomTypesBundle\DBAL\TypeRegistry;
use Ekapusta\DoctrineCustomTypesBundle\DBAL\Types\BinaryUuidType;
use PHPUnit\Framework\TestCase;
use stdClass;

use function hex2bin;

final class BinaryUuidTypeTest extends TestCase
{
    const UUID = 'cae20c89-52ff-5c8e-82b3-29e5bf9e7001';

    const BYTES_HEX = 'cae20c8952ff5c8e82b329e5bf9e7001';

    /** @var BinaryUuidType */
    private $type;

    /** @var MySqlPlatform */
    private $platform;

    protected function setUp(): void
    {
        if (!Type::hasType(BinaryUuidType::TYPE_NAME)) {
            Type::addType(BinaryUuidType::TYPE_NAME, BinaryUuidType::class);
        }

        $this->type = Type::getType(BinaryUuidType::TYPE_NAME);
        $this->platform = new MySqlPlatform();
    }

    public function testType()
    {
        self::assertSame('binary_uuid', $this->type->getName(), 'type name');
        self::assertSame('BINARY(16)', $this->type->getSQLDeclaration([], $this->platform), 'fixed 16 bytes column');
        self::assertTrue($this->type->requiresSQLCommentHint($this->platform), 'comment distinguishes it from plain binary');
    }

    /**
     * @dataProvider phpValues
     */
    public function testConvertToPHPValue($databaseValue, $expected)
    {
        self::assertSame($expected, $this->type->convertToPHPValue($databaseValue, $this->platform), 'bytes become RFC 4122 string');
    }

    public static function phpValues()
    {
        return [
            'null' => [null, null],
            'bytes' => [hex2bin(self::BYTES_HEX), self::UUID],
        ];
    }

    /**
     * @dataProvider invalidDatabaseValues
     */
    public function testInvalidValueToPHP($value)
    {
        $this->expectException(ConversionException::class);

        $this->type->convertToPHPValue($value, $this->platform);
    }

    public static function invalidDatabaseValues()
    {
        return [
            'empty string' => [''],
            'too short' => ['abc'],
            'rfc string' => [self::UUID],
            'integer' => [42],
        ];
    }

    /**
     * @dataProvider databaseValues
     */
    public function testConvertToDatabaseValue($phpValue, $expected)
    {
        self::assertSame($expected, $this->type->convertToDatabaseValue($phpValue, $this->platform), 'RFC 4122 string becomes 16 bytes');
    }

    public static function databaseValues()
    {
        return [
            'null' => [null, null],
            'empty string' => ['', null],
            'lower case' => [self::UUID, hex2bin(self::BYTES_HEX)],
            'upper case' => ['CAE20C89-52FF-5C8E-82B3-29E5BF9E7001', hex2bin(self::BYTES_HEX)],
        ];
    }

    /**
     * @dataProvider invalidPHPValues
     */
    public function testInvalidValueToDatabase($value)
    {
        $this->expectException(ConversionException::class);

        $this->type->convertToDatabaseValue($value, $this->platform);
    }

    public static function invalidPHPValues()
    {
        return [
            'not uuid' => ['not-a-uuid'],
            'without dashes' => [self::BYTES_HEX],
            'bytes' => [hex2bin(self::BYTES_HEX)],
            '16 chars' => ['abcdefghijklmnop'],
            'trailing newline' => [self::UUID."\n"],
            'integer' => [42],
            'object' => [new stdClass()],
        ];
    }

    public function testRoundTrip()
    {
        self::assertSame(self::UUID, $this->type->convertToPHPValue($this->type->convertToDatabaseValue(self::UUID, $this->platform), $this->platform), 'value survives round trip');
    }

    public function testRegistration()
    {
        $registry = new TypeRegistry();

        self::assertSame(BinaryUuidType::class, $registry->getDoctrineMapping()['binary_uuid'], 'type is available to Doctrine');
        self::assertArrayNotHasKey('binary', $registry->getDatabaseMapping('pdo_mysql'), 'plain binary columns are not taken over');
    }
}
