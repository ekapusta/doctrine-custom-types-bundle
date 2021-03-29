<?php

namespace Ekapusta\DoctrineCustomTypesBundle\DBAL\Types;

use Doctrine\DBAL\Types\Type;

abstract class BaseType extends Type
{
    protected $name = null;

    public function getName()
    {
        return $this->name;
    }
}
