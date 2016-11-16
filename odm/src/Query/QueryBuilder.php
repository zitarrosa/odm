<?php
namespace Flaubert\Persistence\ODM\Query;

use Flaubert\Persistence\ODM\ElasticEntityManager;

/**
 * @author Carlos Frutos <carlos@kiwing.it>
 */
class QueryBuilder
{
	/**
	 * @var ElasticEntityManager
	 */
	protected $em;

	/**
	 * @param ElasticEntityManager $em Entity manager
	 */
	public function __construct(ElasticEntityManager $em)
	{
		$this->em = $em;
	}

	/**
	 * @return Expr
	 */
	public function expr()
	{
		return $this->em->getExpressionBuilder();
	}
}