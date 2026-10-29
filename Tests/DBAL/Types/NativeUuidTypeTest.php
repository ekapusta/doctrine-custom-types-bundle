<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\DBAL\Types;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Ekapusta\DoctrineCustomTypesBundle\DBAL\TypeRegistry;
use Ekapusta\DoctrineCustomTypesBundle\DBAL\Types\NativeUuidType;
use PHPUnit\Framework\TestCase;
use stdClass;

use function hex2bin;

final class NativeUuidTypeTest extends TestCase
{
    /** @var NativeUuidType */
    private $type;

    /** @var MySqlPlatform */
    private $platform;

    protected function setUp(): void
    {
        if (!Type::hasType(NativeUuidType::TYPE_NAME)) {
            Type::addType(NativeUuidType::TYPE_NAME, NativeUuidType::class);
        }

        $this->type = Type::getType(NativeUuidType::TYPE_NAME);
        $this->platform = new MySqlPlatform();
    }

    public function testType()
    {
        self::assertSame('native_uuid', $this->type->getName());
        self::assertSame('UUID', $this->type->getSQLDeclaration([], $this->platform));
        self::assertFalse($this->type->requiresSQLCommentHint($this->platform));
    }

    /**
     * @dataProvider values
     */
    public function testValue($value)
    {
        self::assertSame($value, $this->type->convertToDatabaseValue($value, $this->platform));
        self::assertSame($value, $this->type->convertToPHPValue($value, $this->platform));
    }

    public function values()
    {
        return [
            [null],
            ['cae20c89-52ff-5c8e-82b3-29e5bf9e7001'],
        ];
    }

    /**
     * @dataProvider normalizedValues
     */
    public function testNormalizedValue($value, $expected)
    {
        self::assertSame($expected, $this->type->convertToDatabaseValue($value, $this->platform), 'database gets RFC 4122 string');
        self::assertSame($expected, $this->type->convertToPHPValue($value, $this->platform), 'php gets RFC 4122 string');
    }

    public static function normalizedValues()
    {
        return [
            'empty string' => ['', null],
            'upper case' => ['CAE20C89-52FF-5C8E-82B3-29E5BF9E7001', 'CAE20C89-52FF-5C8E-82B3-29E5BF9E7001'],
        ];
    }

    /**
     * @dataProvider invalidValues
     */
    public function testInvalidValueToDatabase($value)
    {
        $this->expectException(ConversionException::class);

        $this->type->convertToDatabaseValue($value, $this->platform);
    }

    /**
     * @dataProvider invalidValues
     */
    public function testInvalidValueToPHP($value)
    {
        $this->expectException(ConversionException::class);

        $this->type->convertToPHPValue($value, $this->platform);
    }

    public static function invalidValues()
    {
        return [
            'not uuid' => ['not-a-uuid'],
            'without dashes' => ['cae20c8952ff5c8e82b329e5bf9e7001'],
            'binary(16) bytes' => [hex2bin('cae20c8952ff5c8e82b329e5bf9e7001')],
            '16 chars' => ['abcdefghijklmnop'],
            'trailing newline' => ["cae20c89-52ff-5c8e-82b3-29e5bf9e7001\n"],
            'integer' => [42],
            'object' => [new stdClass()],
        ];
    }

    public function testRegistration()
    {
        $registry = new TypeRegistry();

        self::assertSame(NativeUuidType::class, $registry->getDoctrineMapping()['native_uuid']);
        self::assertArrayNotHasKey('uuid', $registry->getDoctrineMapping());
        self::assertSame('native_uuid', $registry->getDatabaseMapping('pdo_mysql')['uuid']);
        self::assertArrayNotHasKey('uuid', $registry->getDatabaseMapping('pdo_pgsql'));
    }
}
