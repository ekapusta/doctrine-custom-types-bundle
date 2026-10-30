<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Value;

use InvalidArgumentException;
use JsonSerializable;

/**
 * @see https://www.postgresql.org/docs/current/static/cube.html
 */
class PointSet extends Base implements JsonSerializable
{
    /** @var array<string, Point> */
    private array $points = [];

    private int $dimension = 0;

    /**
     * @param int|float|string|array|Point ...$points anything Point constructor accepts as single argument
     */
    public function __construct(mixed ...$points)
    {
        if (empty($points)) {
            $points = [new Point(0), new Point(1)];
        }
        $this->setPoints($points);
    }

    public function jsonSerialize(): array
    {
        return array_values($this->points);
    }

    public function getFirstPoint(): Point
    {
        return reset($this->points);
    }

    public function getLastPoint(): Point
    {
        return end($this->points);
    }

    protected function isPropertiesEqual(Base $o): bool
    {
        return array_keys($this->points) == array_keys($o->points);
    }

    private function setPoints(array $points): void
    {
        if (count($points) <= 1) {
            throw new InvalidArgumentException('Set should have more than one point');
        }

        $this->points = [];
        $this->dimension = 0;
        foreach ($points as $pointParameter) {
            $this->addPoint($pointParameter);
        }
    }

    private function addPoint(mixed $pointParameter): void
    {
        $point = new Point($pointParameter);

        if (0 === $this->dimension) {
            $this->dimension = $point->getDimension();
        } elseif ($point->getDimension() !== $this->dimension) {
            throw new InvalidArgumentException('Points dimensions must be equals!');
        }
        $hashKey = json_encode($point);
        if (isset($this->points[$hashKey])) {
            throw new InvalidArgumentException('Points must be unique!');
        }
        $this->points[$hashKey] = $point;
    }
}
