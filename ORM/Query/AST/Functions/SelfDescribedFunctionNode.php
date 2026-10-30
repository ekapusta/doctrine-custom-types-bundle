<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;

abstract class SelfDescribedFunctionNode extends FunctionNode
{
    public const RETURN_TYPE_STRING = 'string';
    public const RETURN_TYPE_NUMERIC = 'numeric';
    public const RETURN_TYPE_DATETIME = 'datetime';

    protected static string $compatibilityDriverRegexp = '//';

    public static function getName(): string
    {
        $name = static::class;

        $name = preg_replace('/Function$/', '', $name);
        $name = preg_replace('/.*\\\([^\\\]+)$/', '\1', $name);
        $name = preg_replace('/[A-Z]/', '_$0', $name);
        $name = ltrim($name, '_');

        return strtoupper($name);
    }

    public static function getReturnType(): string
    {
        return self::RETURN_TYPE_NUMERIC;
    }

    public static function isCompatibleWith(string $driver): bool
    {
        return (bool) preg_match(static::$compatibilityDriverRegexp, $driver);
    }
}
