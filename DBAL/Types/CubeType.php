<?php

namespace Ekapusta\DoctrineCustomTypesBundle\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Ekapusta\DoctrineCustomTypesBundle\Value\PointSet;
use Ekapusta\DoctrineCustomTypesBundle\Value\Point;

class CubeType extends BaseType
{
    protected $name = 'cube';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'cube';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return $value;
        }

        if ($value instanceof Point) {
            return $this->formatPoint($value);
        }

        if ($value instanceof PointSet) {
            return $this->formatCube($value);
        }

        throw ConversionException::conversionFailed(var_export($value, true), $this->name);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        if (!preg_match_all('/\([^\)]*\)/', $value, $matches)) {
            throw ConversionException::conversionFailed($value, $this->name);
        }

        $match = $matches[0];
        try {
            if (count($match) == 2) {
                return new PointSet($this->parsePoint($match[0]), $this->parsePoint($match[1]));
            }

            return $this->parsePoint($match[0]);
        } catch (\InvalidArgumentException $e) {
            throw ConversionException::conversionFailedFormat($value, $this->name, $e->getMessage());
        }
    }

    private function formatPoint(Point $point)
    {
        return sprintf('(%s)', implode(', ', $point->toArray()));
    }

    private function formatCube(PointSet $cube)
    {
        return implode(', ', [
            $this->formatPoint($cube->getFirstPoint()),
            $this->formatPoint($cube->getLastPoint())
        ]);
    }

    private function parsePoint($value)
    {
        $values = preg_split('/, */', trim($value, '()'));
        if (count($values) == 1 && trim($values[0]) == '') {
            return null;
        }
        return new Point($values);
    }
}
