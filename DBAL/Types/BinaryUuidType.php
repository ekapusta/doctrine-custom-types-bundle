<?php

namespace Ekapusta\DoctrineCustomTypesBundle\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;

use function bin2hex;
use function gettype;
use function hex2bin;
use function is_string;
use function str_replace;
use function strlen;
use function substr;

final class BinaryUuidType extends AbstractUuidType
{
    const TYPE_NAME = 'binary_uuid';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return $platform->getBinaryTypeDeclarationSQL([
            'length' => 16,
            'fixed' => true,
        ]);
    }

    /**
     * @return string|null
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        if (!is_string($value) || strlen($value) !== 16) {
            throw ConversionException::conversionFailedFormat(is_string($value) ? bin2hex($value) : gettype($value), self::TYPE_NAME, '16 bytes');
        }

        $hex = bin2hex($value);

        return substr($hex, 0, 8).'-'.substr($hex, 8, 4).'-'.substr($hex, 12, 4).'-'.substr($hex, 16, 4).'-'.substr($hex, 20);
    }

    /**
     * @return string|null
     *
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $uuid = $this->normalizeRfc4122($value);

        return $uuid === null ? null : hex2bin(str_replace('-', '', $uuid));
    }

    public function getName()
    {
        return self::TYPE_NAME;
    }
}
