<?php

namespace Ekapusta\DoctrineCustomTypesBundle\DBAL;

use Ekapusta\DoctrineCustomTypesBundle\DBAL\Types\BinaryHashType;
use Ekapusta\DoctrineCustomTypesBundle\DBAL\Types\BinaryUuidType;
use Ekapusta\DoctrineCustomTypesBundle\DBAL\Types\CubeType;
use Ekapusta\DoctrineCustomTypesBundle\DBAL\Types\EnumType;
use Ekapusta\DoctrineCustomTypesBundle\DBAL\Types\NativeUuidType;
use Ekapusta\DoctrineCustomTypesBundle\DBAL\Types\UnescapedJsonType;

class TypeRegistry
{
    /** @var array<string, array{string, string|null}> */
    private array $dbToDoctrine = [];

    /** @var array<string, class-string> */
    private array $doctrineToClass = [];

    public function __construct()
    {
        $this->registerType(EnumType::TYPE_NAME, EnumType::class, '/mysql/i');
        $this->registerType(CubeType::TYPE_NAME, CubeType::class, '/pgsql/i');
        $this->registerType(UnescapedJsonType::TYPE_NAME, UnescapedJsonType::class, '/pgsql/i');
        $this->registerType(BinaryHashType::TYPE_NAME, BinaryHashType::class, '/mysql/i');
        $this->registerType('uuid', NativeUuidType::class, '/mysql/i');
        $this->registerType(BinaryUuidType::TYPE_NAME, BinaryUuidType::class, '/mysql/i');
    }

    /**
     * @param class-string $className type class with TYPE_NAME constant
     */
    private function registerType(string $databaseName, string $className, ?string $compatibleDriverMask = null): static
    {
        $doctrineName = $className::TYPE_NAME;
        $this->dbToDoctrine[$databaseName] = [$doctrineName, $compatibleDriverMask];
        $this->doctrineToClass[$doctrineName] = $className;

        return $this;
    }

    /**
     * @return array<string, string> database type => doctrine type
     */
    public function getDatabaseMapping(string $connectionDriver): array
    {
        $result = [];
        foreach ($this->dbToDoctrine as $databaseName => [$doctrineName, $compatibleDriverMask]) {
            if (null === $compatibleDriverMask || preg_match($compatibleDriverMask, $connectionDriver)) {
                $result[$databaseName] = $doctrineName;
            }
        }

        return $result;
    }

    /**
     * @return array<string, class-string> doctrine type => class
     */
    public function getDoctrineMapping(): array
    {
        return $this->doctrineToClass;
    }
}
