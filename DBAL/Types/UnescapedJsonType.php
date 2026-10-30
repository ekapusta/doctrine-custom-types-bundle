<?php

namespace Ekapusta\DoctrineCustomTypesBundle\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;

class UnescapedJsonType extends JsonType
{
    public const TYPE_NAME = 'unescaped_json';

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        return json_decode($value, true);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string|false
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function getName(): string
    {
        return self::TYPE_NAME;
    }
}
