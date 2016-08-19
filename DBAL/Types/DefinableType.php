<?php

namespace Ekapusta\DoctrineCustomTypesBundle\DBAL\Types;

use Doctrine\DBAL\Types\Type;

abstract class DefinableType extends Type implements ColumnDefinerInterface
{
    protected $name = null;

    public function getName()
    {
        return $this->name;
    }
}