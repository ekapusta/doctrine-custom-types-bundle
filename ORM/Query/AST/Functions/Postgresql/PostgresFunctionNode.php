<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\Postgresql;

use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\Lexer;
use Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\SelfDescribedFunctionNode;

/**
 * "NAME" "(" ArithmeticPrimary {"," ArithmeticPrimary} ")" with MIN_ARGUMENTS..MAX_ARGUMENTS arguments.
 */
abstract class PostgresFunctionNode extends SelfDescribedFunctionNode
{
    protected const MIN_ARGUMENTS = 1;
    protected const MAX_ARGUMENTS = 1;

    protected static string $compatibilityDriverRegexp = '/(pgsql|postgres)/';

    /** @var list<Node|string> */
    public array $arguments = [];

    public function getSql(SqlWalker $sqlWalker): string
    {
        $arguments = array_map($sqlWalker->walkArithmeticPrimary(...), $this->arguments);

        return sprintf('%s(%s)', static::getName(), implode(', ', $arguments));
    }

    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->arguments = [$parser->ArithmeticPrimary()];
        while (count($this->arguments) < static::MAX_ARGUMENTS) {
            if (count($this->arguments) >= static::MIN_ARGUMENTS && !$parser->getLexer()->isNextToken(Lexer::T_COMMA)) {
                break;
            }
            $parser->match(Lexer::T_COMMA);
            $this->arguments[] = $parser->ArithmeticPrimary();
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
