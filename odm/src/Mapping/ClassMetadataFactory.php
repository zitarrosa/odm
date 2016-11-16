<?php
namespace Zitarrosa\ODM\Mapping;

use Doctrine\Common\Persistence\Mapping\AbstractClassMetadataFactory;
use Doctrine\Common\Persistence\Mapping\ClassMetadata as ClassMetadataInterface;
use Doctrine\Common\Persistence\Mapping\ReflectionService;
use Zitarrosa\ODM\EntityManagerInterface;

/**
 * Creates metadata objects used to map classes to ElasticSearch documents
 *
 * @author Carlos Frutos <carlos@kiwing.it>
 */
class ClassMetadataFactory extends AbstractClassMetadataFactory
{
    /**
     * @var EntityManagerInterface|null
     */
    protected $em;

    /**
     * @var \Doctrine\Common\Persistence\Mapping\Driver\MappingDriver
     */
    private $driver;

    /**
     * {@inheritdoc}
     */
    protected function initialize()
    {
        $this->driver = $this->em->getConfiguration()->getMetadataDriverImpl();
        $this->initialized = true;
    }

    /**
     * Set entity manager
     * 
     * @var EntityManagerInterface $em Entity manager
     */
    public function setEntityManager(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Gets the fully qualified class-name from the namespace alias.
     *
     * @param string $namespaceAlias
     * @param string $simpleClassName
     *
     * @return string
     */
    protected function getFqcnFromAlias($namespaceAlias, $simpleClassName)
    {

    }

    /**
     * Returns the mapping driver implementation.
     *
     * @return \Doctrine\Common\Persistence\Mapping\Driver\MappingDriver
     */
    protected function getDriver()
    {
        return $this->driver;
    }

    /**
     * {@inheritdoc}
     */
    protected function wakeupReflection(ClassMetadataInterface $class, ReflectionService $reflService)
    {
        /**
         * @var $class ClassMetadata 
         */
        $class->wakeupReflection($reflService);
    }

    /**
     * Initializes Reflection after ClassMetadata was constructed.
     *
     * @param ClassMetadata     $class
     * @param ReflectionService $reflService
     *
     * @return void
     */
    protected function initializeReflection(ClassMetadataInterface $class, ReflectionService $reflService)
    {

    }

    /**
     * Checks whether the class metadata is an entity.
     *
     * This method should return false for mapped superclasses or embedded classes.
     *
     * @param ClassMetadata $class
     *
     * @return boolean
     */
    protected function isEntity(ClassMetadataInterface $class)
    {

    }

    /**
     * Actually loads the metadata from the underlying metadata.
     *
     * @param ClassMetadata      $class
     * @param ClassMetadata|null $parent
     * @param bool               $rootEntityFound
     * @param array              $nonSuperclassParents All parent class names
     *                                                 that are not marked as mapped superclasses.
     *
     * @return void
     */
    protected function doLoadMetadata($class, $parent, $rootEntityFound, array $nonSuperclassParents)
    {

    }

    /**
     * Creates a new ClassMetadata instance for the given class name.
     *
     * @param string $className
     *
     * @return ClassMetadata
     */
    protected function newClassMetadataInstance($className)
    {

    }
}