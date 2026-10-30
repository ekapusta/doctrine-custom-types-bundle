<?php

namespace Ekapusta\DoctrineCustomTypesBundle\DBAL\Types;

use Doctrine\DBAL\Types\ConversionException;

use function get_debug_type;
use function is_string;
use function preg_match;

/**
 * @internal
 */
abstract class AbstractUuidType extends BaseType
{
    public const RFC_4122_FORMAT = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';

    /**
     * @throws ConversionException
     */
    protected function normalizeRfc4122(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (!is_string($value) || !preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/Di', $value)) {
            throw ConversionException::conversionFailedFormat(is_string($value) ? $value : get_debug_type($value), $this->getName(), self::RFC_4122_FORMAT);
        }

        return $value;
    }
}
