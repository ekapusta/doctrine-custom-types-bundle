<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\EventListener;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\Column;
use Ekapusta\DoctrineCustomTypesBundle\DBAL\Types\BaseType;
use Ekapusta\DoctrineCustomTypesBundle\DBAL\Types\ColumnDefinerInterface;

class DbalDummyType extends BaseType implements ColumnDefinerInterface
{
    public const TYPE_NAME = 'dummy';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'dummy';
    }

    public function getColumnDefinition(array $tableColumn, AbstractPlatform $platform): Column
    {
        return new Column('dummy', $this, []);
    }
}
