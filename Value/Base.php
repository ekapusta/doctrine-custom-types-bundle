<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Value;

abstract class Base
{
    public function equals(mixed $o): bool
    {
        if ($this === $o) {
            return true;
        }
        if (!$o instanceof self || $o::class !== static::class) {
            return false;
        }

        return $this->isPropertiesEqual($o);
    }

    /**
     * @param static $o
     */
    abstract protected function isPropertiesEqual(self $o): bool;
}
