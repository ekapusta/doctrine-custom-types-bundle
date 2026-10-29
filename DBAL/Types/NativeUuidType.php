<?php

namespace Ekapusta\DoctrineCustomTypesBundle\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;

final class NativeUuidType extends AbstractUuidType
{
    const TYPE_NAME = 'native_uuid';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return 'UUID';
    }

    /**
     * @return string|null
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $this->normalizeRfc4122($value);
    }

    /**
     * @return string|null
     *
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $this->normalizeRfc4122($value);
    }

    public function getName()
    {
        return self::TYPE_NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return false;
    }
}
