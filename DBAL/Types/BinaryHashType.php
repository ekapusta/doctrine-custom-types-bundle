<?php

namespace Ekapusta\DoctrineCustomTypesBundle\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;

use function bin2hex;
use function hex2bin;
use function trim;

class BinaryHashType extends BaseType
{
    const TYPE_NAME = 'binary_hash';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return $platform->getBinaryTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return trim($value) === '' ? '' : bin2hex($value);
    }

    /**
     * @return false|string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return hex2bin($value);
    }

    public function getName()
    {
        return self::TYPE_NAME;
    }
}
