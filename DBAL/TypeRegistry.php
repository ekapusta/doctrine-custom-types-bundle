<?php

namespace Ekapusta\DoctrineCustomTypesBundle\DBAL;

class TypeRegistry
{

    private $dbToDoctrine = [];

    private $doctrineToClass = [];

    public function __construct()
    {
    }

    private function registerType($databaseName, $doctrineName, $className)
    {
        $this->dbToDoctrine[$databaseName] = $doctrineName;
        $this->doctrineToClass[$doctrineName] = $className;
        return $this;
    }

    public function getDatabaseMapping()
    {
        return $this->dbToDoctrine;
    }

    public function getDoctrineMapping()
    {
        return $this->doctrineToClass;
    }
}
