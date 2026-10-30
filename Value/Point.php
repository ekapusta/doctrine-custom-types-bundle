<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Value;

use InvalidArgumentException;
use JsonSerializable;

/**
 * @see https://www.postgresql.org/docs/current/static/cube.html
 */
class Point extends Base implements JsonSerializable
{
    /** @var list<float> */
    private array $values = [];

    /**
     * @param int|float|string|array|Point ...$values coordinates, or single array of them, or point to copy
     */
    public function __construct(mixed ...$values)
    {
        if (count($values) === 1) {
            $first = $values[0];
            $values = match (true) {
                is_array($first) => $first,
                $first instanceof self => $first->values,
                default => [$first],
            };
        }
        $this->setValues($values);
    }

    public function jsonSerialize(): array
    {
        return $this->values;
    }

    /**
     * @return list<float>
     */
    public function toArray(): array
    {
        return $this->values;
    }

    public function getDimension(): int
    {
        return count($this->values);
    }

    protected function isPropertiesEqual(Base $o): bool
    {
        return $this->getDimension() === $o->getDimension()
            && $this->values == $o->values;
    }

    private function setValues(array $values): void
    {
        $this->values = [];

        if (0 === count($values)) {
            $values = [0];
        }
        foreach ($values as $value) {
            if (!is_numeric($value)) {
                throw new InvalidArgumentException('Multi point coordinate must be numeric.');
            }
            $this->values[] = (float) $value;
        }
    }
}
