<?php
namespace Flaubert\Persistence\Elastic\ODM\Expressions\Builders;

use Flaubert\Persistence\Elastic\ODM\Expressions\RangeExpression;

class RangeBuilder extends ExpressionBuilder
{
    /**
     * @var mixed
     */
    protected $field;

    /**
     * @var array
     */
    protected $params;

    /**
     * @param mixed $field Field name
     */
    public function __construct($field)
    {
        $this->field = $field;

        $this->params = [
            '>=' => null,
            '>' => null,
            '<=' => null,
            '<' => null
        ];
    }

    /**
     * Greater-than or equal to
     *
     * @param mixed $value
     *
     * @return self
     */
    public function gte($value)
    {
        if (!empty($this->params['>'])) {
            $this->params['>'] = null;
        }

        $this->params['>='] = $value;

        return $this;
    }

    /**
     * Greater-than
     *
     * @param mixed $value
     *
     * @return self
     */
    public function gt($value)
    {
        if (!empty($this->params['>='])) {
            $this->params['>='] = null;
        }

        $this->params['>'] = $value;

        return $this;
    }

    /**
     * Less-than or equal to
     *
     * @param mixed $value
     *
     * @return self
     */
    public function lte($value)
    {
        if (!empty($this->params['<'])) {
            $this->params['<'] = null;
        }

        $this->params['<='] = $value;

        return $this;
    }

    /**
     * Less-than
     *
     * @param mixed $value
     *
     * @return self
     */
    public function lt($value)
    {
        if (!empty($this->params['<='])) {
            $this->params['<='] = null;
        }

        $this->params['<'] = $value;

        return $this;
    }


    /**
     * {@inheritDoc}
     */
    public function build()
    {
        return new RangeExpression($this->field, $this->params);
    }
}