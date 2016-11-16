<?php
namespace Zitarrosa\ODM\Persisters\Entity;

use Zitarrosa\ODM\EntityManagerInterface;
use Zitarrosa\ODM\Mapping\ClassMetadata;

class BasicEntityPersister implements EntityPersisterInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var ClassMetadata
     */
    protected $class;

    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        $this->em = $em;
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function getClassMetadata()
    {
        return $this->class;
    }

    /**
     * {@inheritdoc}
     */
    public function loadById(array $identifier, $entity = null)
    {
        return $this->load($identifier, $entity);
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $criteria, $entity = null, $assoc = null, array $hints = array(), $lockMode = null, $limit = null, array $orderBy = null)
    {
        $dsl = $this->getSelectDSL($criteria, $assoc, $lockMode, $limit, null, $orderBy);
        list($params, $types) = $this->expandParameters($criteria);
        $stmt = $this->conn->executeQuery($sql, $params, $types);

        if ($entity !== null) {
            $hints[Query::HINT_REFRESH]         = true;
            $hints[Query::HINT_REFRESH_ENTITY]  = $entity;
        }

        $hydrator = $this->em->newHydrator($this->currentPersisterContext->selectJoinSql ? Query::HYDRATE_OBJECT : Query::HYDRATE_SIMPLEOBJECT);
        $entities = $hydrator->hydrateAll($stmt, $this->currentPersisterContext->rsm, $hints);

        return $entities ? $entities[0] : null;
    }
}