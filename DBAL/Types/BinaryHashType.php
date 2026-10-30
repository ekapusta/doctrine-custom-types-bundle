<?php

namespace Ekapusta\DoctrineCustomTypesBundle\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;

use function bin2hex;
use function hex2bin;
use function trim;

final class BinaryHashType extends BaseType
{
    public const TYPE_NAME = 'binary_hash';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getBinaryTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): string
    {
        $value = (string) $value;

        return trim($value) === '' ? '' : bin2hex($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string|false
    {
        return hex2bin((string) $value);
    }
}
