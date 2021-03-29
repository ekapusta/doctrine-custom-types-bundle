<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Value;

/**
 * @see https://www.postgresql.org/docs/current/static/cube.html
 */
class PointSet extends Base implements \JsonSerializable
{
    /**
     * @var Point[]
     */
    private $points = [];

    private $dimension = 0;

    public function __construct(/* ... */)
    {
        $args = func_get_args();
        if (empty($args)) {
            $args = [new Point(0), new Point(1)];
        }
        $this->setPoints($args);
    }

    public function jsonSerialize()
    {
        return array_values($this->points);
    }

    /**
     * @return Point
     */
    public function getFirstPoint()
    {
        return reset($this->points);
    }

    /**
     * @return Point
     */
    public function getLastPoint()
    {
        return end($this->points);
    }

    protected function isPropertiesEqual($o)
    {
        /* @var $o PointSet */
        return array_keys($this->points) == array_keys($o->points);
    }

    /**
     * @param array|Point[] $points
     *
     * @return self
     */
    private function setPoints(array $points)
    {
        if (count($points) <= 1) {
            throw new \InvalidArgumentException('Set should have more than one point');
        }

        $this->points = [];
        $this->dimension = 0;
        foreach ($points as $pointParameter) {
            $this->addPoint($pointParameter);
        }

        return $this;
    }

    private function addPoint($pointParameter)
    {
        $point = new Point($pointParameter);

        if (0 == $this->dimension) {
            $this->dimension = $point->getDimension();
        } elseif ($point->getDimension() != $this->dimension) {
            throw new \InvalidArgumentException('Points dimensions must be equals!');
        }
        $hashKey = json_encode($point);
        if (isset($this->points[$hashKey])) {
            throw new \InvalidArgumentException('Points must be unique!');
        }
        $this->points[$hashKey] = $point;

        return $this;
    }
}
