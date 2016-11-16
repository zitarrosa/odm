<?php
namespace Flaubert\Persistence\Elastic\ODM\Expressions\Builders;

use PhpInterop\Objects\IArrayable;

class ExpressionBuilder implements IArrayable
{
    /**
     * Build the expression
     *
     * @return Flaubert\Persistence\Elastic\ODM\Expressions\Expression
     */
    public function build()
    {
        throw new \Exception('build not defined');
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $expr = $this->build();

        return $expr->toArray();
    }
}