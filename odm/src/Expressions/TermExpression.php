<?php
namespace Flaubert\Persistence\Elastic\ODM\Expressions;

/**
 * @author Carlos Frutos <carlos@kiwing.it>
 */
class TermExpression extends Expression
{
	/**
	 * @var mixed $field
	 */
	protected $field;

	/**
	 * @var mixed $value
	 */
	protected $value;

	/**
	 * @param mixed $field Field name
	 * @param mixed $value Match value
	 */
	public function __construct($field, $value)
	{
		$this->field = $field;
		$this->value = $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function toArray()
	{
		return ['term' => [$this->field => $this->value]];
	}
}