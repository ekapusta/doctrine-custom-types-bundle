<?php

namespace Ekapusta\DoctrineCustomTypesBundle\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Ekapusta\DoctrineCustomTypesBundle\Value\Point;
use Ekapusta\DoctrineCustomTypesBundle\Value\PointSet;
use InvalidArgumentException;

class CubeType extends BaseType
{
    public const TYPE_NAME = 'cube';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'cube';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return match (true) {
            null === $value => null,
            $value instanceof Point => $this->formatPoint($value),
            $value instanceof PointSet => $this->formatCube($value),
            default => throw ConversionException::conversionFailed(var_export($value, true), self::TYPE_NAME),
        };
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Point|PointSet|null
    {
        if (empty($value)) {
            return null;
        }

        $matches = [];
        if (!preg_match_all('/\([^\)]*\)/', $value, $matches)) {
            throw ConversionException::conversionFailed($value, self::TYPE_NAME);
        }

        $match = $matches[0];
        try {
            if (count($match) === 2) {
                return new PointSet($this->parsePoint($match[0]), $this->parsePoint($match[1]));
            }

            return $this->parsePoint($match[0]);
        } catch (InvalidArgumentException $e) {
            throw ConversionException::conversionFailedFormat($value, self::TYPE_NAME, $e->getMessage());
        }
    }

    private function formatPoint(Point $point): string
    {
        return sprintf('(%s)', implode(', ', $point->toArray()));
    }

    private function formatCube(PointSet $cube): string
    {
        return implode(', ', [
            $this->formatPoint($cube->getFirstPoint()),
            $this->formatPoint($cube->getLastPoint()),
        ]);
    }

    private function parsePoint(string $value): ?Point
    {
        $values = preg_split('/, */', trim($value, '()'));
        if (count($values) === 1 && trim($values[0]) === '') {
            return null;
        }

        return new Point($values);
    }
}
