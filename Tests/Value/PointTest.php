<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\Value;

use Ekapusta\DoctrineCustomTypesBundle\Value\Point;

class PointTest extends \PHPUnit_Framework_TestCase
{

    public function testEmptyPointEqualsToOneDimensionalZeroPoint()
    {
        $this->assertEquals(new Point(0), new Point());
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage numeric
     */
    public function testNotCreatedFromNonNumericValues()
    {
        new Point(1, 'two');
    }

    public function testCreatedMultiFromArray()
    {
        return new Point(['1', 2, 3]);
    }

    /**
     * @depends testCreatedMultiFromArray
     */
    public function testExportedToArray(Point $Point)
    {
        $this->assertSame([1.0, 2.0, 3.0], $Point->toArray());
    }

    /**
     * @depends testCreatedMultiFromArray
     */
    public function testMultiEqualsToEachOther(Point $Point)
    {
        $this->assertTrue($Point->equals(new Point(1.0, 2, '3')));
    }

    /**
     * @depends testCreatedMultiFromArray
     */
    public function testMultiEqualsToSelf(Point $Point)
    {
        $this->assertTrue($Point->equals($Point));
    }

    /**
     * @dataProvider dataForNotEqualsTo
     * @depends testCreatedMultiFromArray
     */
    public function testNotEqualsTo($other, Point $Point)
    {
        $this->assertFalse($Point->equals($other));
    }

    public function dataForNotEqualsTo()
    {
        return [
            [null],
            [[1, 2, 3]],
            [new \stdClass()],
            [new Point(1, 2, 3, 4)],
            [new Point(3, 2, 1)],
        ];
    }
}
