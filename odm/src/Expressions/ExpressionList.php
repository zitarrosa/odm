<?php
namespace Flaubert\Persistence\Elastic\ODM\Expressions;

use Flaubert\Persistence\Elastic\ODM\Expressions\Builders\RangeBuilder;

class ExpressionList
{
    /**
     * @return TermExpression
     */
    public function term($field, $value)
    {
        return new TermExpression($field, $value);
    }

    /**
     * @return NotExpression
     */
    public function not(Expression $expr)
    {
        return new NotExpression($expr);
    }

    /**
     * @param mixed $field Field
     *
     * @return RangeExpressionBuilder
     */
    public function rangeOf($field)
    {
        return new RangeBuilder($field);
    }
}