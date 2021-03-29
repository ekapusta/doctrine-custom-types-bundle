<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;

abstract class SelfDescribedFunctionNode extends FunctionNode
{
    const RETURN_TYPE_STRING = 'string';
    const RETURN_TYPE_NUMERIC = 'numeric';
    const RETURN_TYPE_DATETIME = 'datetime';

    protected static $compatibilityDriverRegexp = '//';

    public static function getName()
    {
        $name = static::class;

        $name = preg_replace('/Function$/', '', $name);
        $name = preg_replace('/.*\\\([^\\\]+)$/', '\1', $name);
        $name = preg_replace('/[A-Z]/', '_$0', $name);
        $name = ltrim($name, '_');
        $name = strtoupper($name);

        return $name;
    }

    /**
     * @return string
     */
    public static function getReturnType()
    {
        return self::RETURN_TYPE_NUMERIC;
    }

    public static function isCompatibleWith($driver)
    {
        return preg_match(static::$compatibilityDriverRegexp, $driver);
    }
}
