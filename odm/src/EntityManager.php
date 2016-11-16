<?php
namespace Zitarrosa\ODM;

use Exception;

/**
 * Facade of all ODM subsystems
 *
 * @author Carlos Frutos <carlos@kiwing.it>
 */
final class EntityManager implements EntityManagerInterface
{
    /**
     * @var Configuration
     */
    private $config;

    /**
     * The metadata factory, used to retrieve the ODM metadata of entity classes.
     *
     * @var \Zitarrosa\ODM\Mapping\ClassMetadataFactory
     */
    private $metadataFactory;

    /**
     * The repository factory used to create dynamic repositories.
     *
     * @var \Zitarrosa\ODM\Repository\RepositoryFactoryInterface
     */
    private $repositoryFactory;

    /**
     * The UnitOfWork used to coordinate object-level transactions.
     *
     * @var UnitOfWork
     */
    private $unitOfWork;

    /**
     * @todo
     */
    protected function __construct(Configuration $config)
    {
        $this->config = $config;
        $this->unitOfWork = new UnitOfWork($this);

        $metadataFactoryClassName = $config->getClassMetadataFactoryName();
        $this->metadataFactory = new $metadataFactoryClassName();
        $this->metadataFactory->setEntityManager($this);

        $this->repositoryFactory = $config->getRepositoryFactory();
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
        return $this->repositoryFactory->getRepository($this, $className);
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

    public static function create($connector, Configuration $config)
    {
        if (!$config->getMetadataDriverImpl()) {
            throw new Exception('Metadata driver not defined');
        }

        return new EntityManager($config);
    }
}