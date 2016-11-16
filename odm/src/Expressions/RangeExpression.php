<?php
namespace Flaubert\Persistence\Elastic\ODM\Expressions;

use InvalidArgumentException;

class RangeExpression extends Expression
{
	protected static $validLimits = ['<', '<=', '>', '>='];

	protected static $operatorsMappings = [
		'<' => 'lt',
		'<=' => 'lte',
		'>' => 'gt',
		'>=' => 'gte'
	];

	/**
	 * Expression converted to array
	 *
	 * @var array
	 */
	protected $arrayExpr;

	public function __construct($field, array $limits)
	{
		$limits += [
			'<' => null,
			'<=' => null,
			'>' => null,
			'>=' => null
		];

		$this->doValidation($limits);

		//Convert expression to array
		$limits = array_filter($limits);

		$arrExpr = ['range' => [$field => []]];

		foreach ($limits as $limit => $value) {
			$arrExpr['range'][$field][static::$operatorsMappings[$limit]] = $value;
		}

		$this->arrExpr = $arrExpr;
	}

	/**
	 * Do limits validations
	 *
	 * @param array $limits Limits
	 *
	 * @throws InvalidArgumentException On validation violation
	 *
	 * @return void
	 */
	private function doValidation(array &$limits)
	{
		$invalidLimits = array_diff(array_keys($limits), static::$validLimits);
		if (!empty($invalidLimits)) {
			throw new InvalidArgumentException('There are invalid limits');
		}

		if (!empty($limits['<']) && !empty($limits['<='])) {
			throw new InvalidArgumentException('lt and lte can\'t be defined at same time');
		}

		if (!empty($limits['>']) && !empty($limits['>='])) {
			throw new InvalidArgumentException('gt and gte can\'t be defined at same time');
		}

		if (
			empty($limits['<']) &&
			empty($limits['<=']) &&
			empty($limits['>']) &&
			empty($limits['>='])
		) {
			throw new InvalidArgumentException('At least one limit must be defined');
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function toArray()
	{
		return $this->arrExpr;
	}
}