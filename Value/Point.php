<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Value;

use InvalidArgumentException;
use JsonSerializable;

/**
 * @see https://www.postgresql.org/docs/current/static/cube.html
 */
class Point extends Base implements JsonSerializable
{
    private $values = [];

    public function __construct(/* ... */)
    {
        if (func_num_args() == 1) {
            $first = func_get_arg(0);
            if (is_array($first)) {
                $this->setValues($first);
            } elseif ($first instanceof self) {
                $this->setValues($first->values);
            } else {
                $this->setValues([$first]);
            }
        } else {
            $this->setValues(func_get_args());
        }
    }

    public function jsonSerialize()
    {
        return $this->values;
    }

    public function toArray()
    {
        return $this->values;
    }

    public function getDimension()
    {
        return count($this->values);
    }

    protected function isPropertiesEqual($o)
    {
        /* @var $o Point */
        return true
            && $this->getDimension() == $o->getDimension()
            && $this->values == $o->values
        ;
    }

    /**
     * @return self
     */
    private function setValues(array $values)
    {
        $this->values = [];

        if (0 == count($values)) {
            $values = [0];
        }
        foreach ($values as $value) {
            if (!is_numeric($value)) {
                throw new InvalidArgumentException('Multi point coordinate must be numeric.');
            }
            $this->values[] = (float) $value;
        }

        return $this;
    }
}
