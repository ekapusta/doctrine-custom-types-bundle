<?php

namespace Ekapusta\DoctrineCustomTypesBundle\DBAL;

use Ekapusta\DoctrineCustomTypesBundle\DBAL\Types\EnumType;

class TypeRegistry
{

    private $dbToDoctrine = [];

    private $doctrineToClass = [];

    public function __construct()
    {
        $this->registerType('enum', 'enum', EnumType::class, 'mysql');
    }

    private function registerType($databaseName, $doctrineName, $className, $compatibleDriver = null)
    {
        $this->dbToDoctrine[$databaseName] = [$doctrineName, $compatibleDriver];
        $this->doctrineToClass[$doctrineName] = $className;
        return $this;
    }

    public function getDatabaseMapping($connectionDriver)
    {
        $result = [];
        foreach ($this->dbToDoctrine as $databaseName => $row) {
            list($doctrineName, $compatibleDriver) = $row;
            if (is_null($compatibleDriver) || strstr($connectionDriver, $compatibleDriver)) {
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
