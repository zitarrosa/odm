<?php
namespace Zitarrosa\ODM;

/**
 * Facade of all ODM subsystems
 *
 * @author Carlos Frutos <carlos@kiwing.it>
 */
final class EntityManager implements EntityManagerInterface
{
    /**
     * The metadata factory, used to retrieve the ODM metadata of entity classes.
     *
     * @var \Zitarrosa\ODM\Mapping\ClassMetadataFactory
     */
    private $metadataFactory;

    /**
     * The UnitOfWork used to coordinate object-level transactions.
     *
     * @var UnitOfWork
     */
    private $unitOfWork;

    /**
     * @todo
     */
    protected function __construct()
    {
        $this->unitOfWork = new UnitOfWork($this);
    }

    /**
     * {@inheritdoc}
     */
    public function find($className, $id)
    {
        
    }

    /**
     * {@inheritdoc}
     */
    public function persist($object)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function remove($object)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function merge($object)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function clear($objectName = null)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function detach($object)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function refresh($object)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getRepository($className)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getClassMetadata($className)
    {
        return $this->metadataFactory->getMetadataFor($className);
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadataFactory()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function initializeObject($obj)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function contains($object)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function getUnitOfWork()
    {
        return $this->unitOfWork;
    }
}