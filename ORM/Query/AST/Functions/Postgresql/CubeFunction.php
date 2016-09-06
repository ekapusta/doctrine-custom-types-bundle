<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions\Postgresql;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Makes a new cube
 *
 * "CUBE" "(" ArithmeticPrimary ")"
 * "CUBE" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
 * "CUBE" "(" ArithmeticPrimary "," ArithmeticPrimary "," ArithmeticPrimary ")"
 */
class CubeFunction extends PostgresFunctionNode
{
    public $cube1;
    public $cube2;
    public $cube3;

    public static function getReturnType()
    {
        return self::RETURN_TYPE_STRING;
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        if (!is_null($this->cube3)) {
            return sprintf('%s(%s, %s, %s)', $this->getName(),
                $this->cube1->dispatch($sqlWalker), $this->cube2->dispatch($sqlWalker), $this->cube3->dispatch($sqlWalker));
        }
        if (!is_null($this->cube2)) {
            return sprintf('%s(%s, %s)', $this->getName(),
                $this->cube1->dispatch($sqlWalker), $this->cube2->dispatch($sqlWalker));
        }
        return sprintf('%s(%s)', $this->getName(), $this->cube1->dispatch($sqlWalker));
    }

    public function parse(Parser $parser)
    {
        $lexer = $parser->getLexer();

        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->cube1 = $parser->ArithmeticPrimary();
            if ($lexer->isNextToken(Lexer::T_COMMA)) {
            $parser->match(Lexer::T_COMMA);
            $this->cube2 = $parser->ArithmeticPrimary();
        }
        if ($lexer->isNextToken(Lexer::T_COMMA)) {
            $parser->match(Lexer::T_COMMA);
            $this->cube3 = $parser->ArithmeticPrimary();
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
