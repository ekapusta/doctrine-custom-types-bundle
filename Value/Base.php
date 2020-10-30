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
            || is_null($o)
            || !is_object($o)
            || get_class($this) != get_class($o)
        ) {
            return false;
        }

        return $this->isPropertiesEqual($o);
    }

    /**
     * @codeCoverageIgnore
     *
     * @return boolean
     */
    abstract protected function isPropertiesEqual($o);
}