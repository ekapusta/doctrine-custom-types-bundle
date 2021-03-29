<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Value;

abstract class Base
{
    public function equals($o)
    {
        if ($this === $o) {
            return true;
        }
        if (false
            || null === $o
            || !is_object($o)
            || static::class != get_class($o)
        ) {
            return false;
        }

        return $this->isPropertiesEqual($o);
    }

    /**
     * @codeCoverageIgnore
     *
     * @return bool
     */
    abstract protected function isPropertiesEqual($o);
}
