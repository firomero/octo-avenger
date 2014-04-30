<?php

namespace Planillas\CoreBundle\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\Lexer;

class DateFormat extends FunctionNode
{
    public $dateExpression = null;
    public $formatExpression = null;

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER); // (2)

        $parser->match(Lexer::T_OPEN_PARENTHESIS); // (3)
        $this->dateExpression = $parser->ArithmeticPrimary(); // (4)
        $parser->match(Lexer::T_COMMA); // (5)
        $this->formatExpression = $parser->ArithmeticPrimary(); // (6)
        $parser->match(Lexer::T_CLOSE_PARENTHESIS); // (3)
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        return 'DATE_FORMAT(' .
        $this->dateExpression->dispatch($sqlWalker) . ', ' .
        $this->formatExpression->dispatch($sqlWalker) .
        ')'; // (7)
    }
} 