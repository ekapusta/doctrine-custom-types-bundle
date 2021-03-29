<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\DBAL\Types;

use Doctrine\DBAL\Platforms\PostgreSQL92Platform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Ekapusta\DoctrineCustomTypesBundle\DBAL\Types\CubeType;
use Ekapusta\DoctrineCustomTypesBundle\Value\Point;
use Ekapusta\DoctrineCustomTypesBundle\Value\PointSet;
use PHPUnit\Framework\TestCase;

class CubeTypeTest extends TestCase
{
    /**
     * @var CubeType
     */
    private $type;

    private $platform;

    protected function setUp()
    {
        if (!Type::hasType('cube')) {
            Type::addType('cube', CubeType::class);
        }

        $this->type = Type::getType('cube');
        $this->platform = new PostgreSQL92Platform();
    }

    public function testHasName()
    {
        $this->assertEquals('cube', $this->type->getName());
    }

    public function testSqlDeclarationGenerated()
    {
        $sql = $this->type->getSQLDeclaration([], $this->platform);
        $this->assertEquals('cube', $sql);
    }

    /**
     * @dataProvider dataForConvertToDatabaseValueFailed
     */
    public function testConvertToDatabaseValueFailed($value)
    {
        $this->expectException(ConversionException::class);
        $this->expectExceptionMessage('Could not convert');

        $this->type->convertToDatabaseValue($value, $this->platform);
    }

    public function dataForConvertToDatabaseValueFailed()
    {
        return [
            ['e'],
            ['-'],
            [''],
            [[[]]],
            [new \stdClass()],
        ];
    }

    /**
     * @dataProvider dataForConvertToDatabaseValueSuccessfully
     */
    public function testConvertToDatabaseValueSuccessfully($expectedDatabaseValue, $value)
    {
        $actualDatabaseValue = $this->type->convertToDatabaseValue($value, $this->platform);
        $this->assertEquals($expectedDatabaseValue, $actualDatabaseValue);
    }

    public function dataForConvertToDatabaseValueSuccessfully()
    {
        return [
            [null,              null],
            ['(0)',             new Point()],
            ['(1)',             new Point(1)],
            ['(0.1, 0.2, 0.3)', new Point(0.1, 0.2, 0.3)],
            ['(0), (1)',        new PointSet()],
            ['(1, 2), (-3, 4)', new PointSet(new Point(1, 2), new Point(-3, 4))],
        ];
    }

    /**
     * @dataProvider dataForConvertToPHPValueFailed
     */
    public function testConvertToPHPValueFailed($value)
    {
        $this->expectException(ConversionException::class);

        $this->type->convertToPHPValue($value, $this->platform);
    }

    public function dataForConvertToPHPValueFailed()
    {
        return [
            ['e'],
            ['-'],
            ['-1'],
            ['1, 2, 3'],
            ['(0, 1), (1)'],
        ];
    }

    /**
     * @dataProvider dataForEmptyValueConvertsToPHPValueNull
     */
    public function testEmptyValueConvertsToPHPValueNull($value)
    {
        $this->assertNull($this->type->convertToPHPValue($value, $this->platform));
    }

    public function dataForEmptyValueConvertsToPHPValueNull()
    {
        return [
            [''],
            [null],
            [0],
        ];
    }

    /**
     * @dataProvider dataForConvertToPHPValueSuccessfully
     */
    public function testConvertToPHPValueSuccessfully($databaseValue, $expectedPhpValue)
    {
        $actualPhpValue = $this->type->convertToPHPValue($databaseValue, $this->platform);
        $this->assertEquals($expectedPhpValue, $actualPhpValue);
    }

    public function dataForConvertToPHPValueSuccessfully()
    {
        return [
            ['()',                  null],
            ['( )',                 null],
            ['(0)',                 new Point(0)],
            ['(0, 1)',              new Point(0, 1)],
            ['(0, -1), (2, 0.03)',  new PointSet(new Point(0, -1), new Point(2, 0.03))],
        ];
    }
}
