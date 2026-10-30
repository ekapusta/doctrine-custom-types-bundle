<?php

namespace Ekapusta\DoctrineCustomTypesBundle\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\Column;
use InvalidArgumentException;

class EnumType extends BaseType implements ColumnDefinerInterface
{
    public const TYPE_NAME = 'enum';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        if (empty($column['values'])) {
            throw new InvalidArgumentException('Enum values are required!');
        }

        $values = array_map(fn ($value) => "'".$value."'", $column['values']);

        return 'enum('.implode(',', $values).')';
    }

    public function getColumnDefinition(array $tableColumn, AbstractPlatform $platform): Column
    {
        $tableColumn += ['default' => null, 'null' => null, 'comment' => null];
        $options = [
            'length' => 0,
            'unsigned' => null,
            'fixed' => null,
            'default' => $tableColumn['default'],
            'notnull' => $tableColumn['null'] !== 'YES',
            'scale' => null,
            'precision' => null,
            'autoincrement' => false,
            'comment' => empty($tableColumn['comment']) ? null : $tableColumn['comment'],
        ];

        $column = new Column($tableColumn['field'], $this, $options);
        $matches = [];
        if (preg_match_all("/'([^']+)'/", $tableColumn['type'], $matches)) {
            $column->setCustomSchemaOption('values', $matches[1]);
        }

        return $column;
    }
}
