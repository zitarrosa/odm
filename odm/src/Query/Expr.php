<?php
namespace Flaubert\Persistence\Elastic\ODM\Query;

use Flaubert\Persistence\Elastic\ODM\Expressions as Ex;

/**
 * @author Carlos Frutos <carlos@kiwing.it>
 */
class Expr
{
    /**
     * @return TermExpression
     */
    public function term($field, $value)
    {
        return new Ex\TermExpression($field, $value);
    }

    /**
     * @return NotExpression
     */
    public function not(Expression $expr)
    {
        return new Ex\NotExpression($expr);
    }

    /**
     * @param mixed $field Field
     *
     * @return RangeExpressionBuilder
     */
    public function rangeOf($field)
    {
        return new Ex\RangeBuilder($field);
    }
}