<?php

namespace Ekapusta\DoctrineCustomTypesBundle\EventListener;

use Doctrine\DBAL\Event\SchemaColumnDefinitionEventArgs;
use Doctrine\DBAL\Types\Type;
use Ekapusta\DoctrineCustomTypesBundle\DBAL\Types\ColumnDefinerInterface;

class DoctrineSchemaColumnDefinitionListener
{
    /**
     * @see \Doctrine\DBAL\Schema\MySqlSchemaManager::_getPortableTableColumnDefinition
     */
    public function onSchemaColumnDefinition(SchemaColumnDefinitionEventArgs $args)
    {
        $tableColumn = array_change_key_case($args->getTableColumn(), CASE_LOWER);
        $tableColumn += ['type' => '', 'data_type' => '', 'typename' => ''];
        $typeCandidates = [$tableColumn['type'], $tableColumn['data_type'], $tableColumn['typename']];
        $dbType = strtolower(current(array_filter($typeCandidates)));
        $dbType = strtok($dbType, '(), ');

        $platform = $args->getConnection()->getDatabasePlatform();
        $type = Type::getType($platform->getDoctrineTypeMapping($dbType));
        if ($type instanceof ColumnDefinerInterface) {
            $args->setColumn($type->getColumnDefinition($tableColumn, $platform));
            $args->preventDefault();
        }
    }
}
