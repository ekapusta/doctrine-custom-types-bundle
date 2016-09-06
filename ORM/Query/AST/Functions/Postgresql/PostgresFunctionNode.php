<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\Postgresql;

use Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\SelfDescribedFunctionNode;

abstract class PostgresFunctionNode extends SelfDescribedFunctionNode
{
    protected static $compatibilityDriverRegexp = '/(pgsql|postgres)/';
}
