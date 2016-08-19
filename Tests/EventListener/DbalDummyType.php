<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\EventListener;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Ekapusta\DoctrineCustomTypesBundle\DBAL\Types\DefinableType;
use Doctrine\DBAL\Schema\Column;

class DbalDummyType extends DefinableType
{
    protected $name = 'dummy';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'dummy';
    }

    public function getColumnDefinition(array $tableColumn, AbstractPlatform $platform)
    {
        return new Column('dummy', $this, []);
    }
}