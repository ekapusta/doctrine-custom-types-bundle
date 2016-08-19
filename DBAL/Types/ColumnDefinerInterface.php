<?php
namespace Ekapusta\DoctrineCustomTypesBundle\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\Column;

interface ColumnDefinerInterface
{

    /**
     * @param array $tableColumn
     * @param AbstractPlatform $platform
     * @return Column
     */
    public function getColumnDefinition(array $tableColumn, AbstractPlatform $platform);
}

