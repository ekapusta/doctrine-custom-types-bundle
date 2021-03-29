<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\Value;

use Ekapusta\DoctrineCustomTypesBundle\Value\Point;
use Ekapusta\DoctrineCustomTypesBundle\Value\PointSet;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PointSetTest extends TestCase
{
    public function testWillNotCreateFromDifferentDimensionCorners()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('dimensions');

        new PointSet(new Point(1, 2, 3), new Point(['1.0', '+2.0']));
    }

    public function testWillNotCreateFromSinglePoints()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('more than one');

        new PointSet(new Point(1, 2, 3));
    }

    public function testWillNotCreateFromSame()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('unique');

        new PointSet(new Point(1), new Point(0), new Point());
    }

    public function testCreatedFromCorners()
    {
        $pointset = new PointSet([1, 2], new Point(['3.0', '+4.0']));

        $this->assertInstanceOf(PointSet::class, $pointset);

        return $pointset;
    }

    public function testDefaultPointSetIsNotEmpty()
    {
        $this->assertEquals(new PointSet(), new PointSet(0, 1));
    }

    public function testPointSetsEquals()
    {
        $one = new PointSet(new Point(1, 2), new Point(-3, 4));
        $another = new PointSet(new Point(1.0, 2), new Point('-3', 4));
        $this->assertTrue($one->equals($another));
    }

    public function testCanBeSerializedToJson()
    {
        $this->assertEquals('[[1,2],[0.3,0.4]]', json_encode(new PointSet([1, 2], [0.3, 0.4])));
    }

    /**
     * @dataProvider dataForPointSetsNotEquals
     */
    public function testPointSetsNotEquals(PointSet $one, PointSet $another)
    {
        $this->assertFalse($one->equals($another));
    }

    public function dataForPointSetsNotEquals()
    {
        return [
            [
                new PointSet(new Point(1, 2), new Point(0, 0)),
                new PointSet(new Point(1.0, 2), new Point('+3', 4)),
            ],
        ];
    }
}
