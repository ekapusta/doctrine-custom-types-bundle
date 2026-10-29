<?php

namespace Ekapusta\DoctrineCustomTypesBundle\DBAL\Types;

use Doctrine\DBAL\Types\ConversionException;

use function gettype;
use function is_string;
use function preg_match;

/**
 * @internal
 */
abstract class AbstractUuidType extends BaseType
{
    const RFC_4122_FORMAT = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';

    /**
     * @return string|null
     *
     * @throws ConversionException
     */
    protected function normalizeRfc4122($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (!is_string($value) || !preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/Di', $value)) {
            throw ConversionException::conversionFailedFormat(is_string($value) ? $value : gettype($value), $this->getName(), self::RFC_4122_FORMAT);
        }

        return $value;
    }
}
