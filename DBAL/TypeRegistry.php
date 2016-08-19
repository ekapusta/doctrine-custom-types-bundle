<?php

namespace Ekapusta\DoctrineCustomTypesBundle\DBAL;

class TypeRegistry
{

    private $dbToDoctrine = [];

    private $doctrineToClass = [];

    public function __construct()
    {
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
