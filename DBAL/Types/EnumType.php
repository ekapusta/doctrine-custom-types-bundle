<?php

namespace Ekapusta\DoctrineCustomTypesBundle\DBAL\Types;

use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\Column;

class EnumType extends DefinableType
{
    protected $name = 'enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        if (empty($fieldDeclaration['values'])) {
            throw new InvalidArgumentException('Enum values are required!');
        }

        $values = array_map(function($val) { return "'".$val."'"; }, $fieldDeclaration['values']);

        return 'enum(' . implode(',', $values) . ')';
    }

    public function getColumnDefinition(array $tableColumn, AbstractPlatform $platform)
    {
        $tableColumn += ['default' => null, 'null' => null, 'comment' => null];
        $options = [
            'length'        => 0,
            'unsigned'      => null,
            'fixed'         => null,
            'default'       => $tableColumn['default'],
            'notnull'       => (bool) ($tableColumn['null'] != 'YES'),
            'scale'         => null,
            'precision'     => null,
            'autoincrement' => false,
            'comment'       => empty($tableColumn['comment']) ? null : $tableColumn['comment'],
        ];

        $column = new Column($tableColumn['field'], $this, $options);
        $matches = [];
        if (preg_match_all("/'([^']+)'/", $tableColumn['type'], $matches)) {
            $column->setCustomSchemaOption('values', $matches[1]);
        }
        return $column;
    }
}