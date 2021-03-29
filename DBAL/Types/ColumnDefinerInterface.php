<?php

namespace Ekapusta\DoctrineCustomTypesBundle\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\Column;

interface ColumnDefinerInterface
{
    /**
     * @return Column
     */
    public function getColumnDefinition(array $tableColumn, AbstractPlatform $platform);
}
