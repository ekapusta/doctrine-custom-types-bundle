<?php

namespace Ekapusta\DoctrineCustomTypesBundle\DBAL;

use Ekapusta\DoctrineCustomTypesBundle\DBAL\Types\BinaryHashType;
use Ekapusta\DoctrineCustomTypesBundle\DBAL\Types\CubeType;
use Ekapusta\DoctrineCustomTypesBundle\DBAL\Types\EnumType;
use Ekapusta\DoctrineCustomTypesBundle\DBAL\Types\UnescapedJsonType;

class TypeRegistry
{
    private $dbToDoctrine = [];

    private $doctrineToClass = [];

    public function __construct()
    {
        $this->registerType('enum', 'enum', EnumType::class, '/mysql/i');
        $this->registerType('cube', 'cube', CubeType::class, '/pgsql/i');
        $this->registerType('unescaped_json', 'unescaped_json', UnescapedJsonType::class, '/pgsql/i');
        $this->registerType('binary_hash', 'binary_hash', BinaryHashType::class, '/mysql/i');
    }

    private function registerType($databaseName, $doctrineName, $className, $compatibleDriverMask = null)
    {
        $this->dbToDoctrine[$databaseName] = [$doctrineName, $compatibleDriverMask];
        $this->doctrineToClass[$doctrineName] = $className;

        return $this;
    }

    public function getDatabaseMapping($connectionDriver)
    {
        $result = [];
        foreach ($this->dbToDoctrine as $databaseName => $row) {
            list($doctrineName, $compatibleDriverMask) = $row;
            if (null === $compatibleDriverMask || preg_match($compatibleDriverMask, $connectionDriver)) {
                $result[$databaseName] = $doctrineName;
            }
        }

        return $result;
    }

    public function getDoctrineMapping()
    {
        return $this->doctrineToClass;
    }
}
