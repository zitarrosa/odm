<?php
namespace Zitarrosa\ODM;

use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Zitarrosa\ODM\Repository\RepositoryFactoryInterface;
use Zitarrosa\ODM\Repository\DefaultRepositoryFactory;
use Zitarrosa\ODM\Mapping\ClassMetadataFactory;

/**
 * ODM Configuration
 *
 * @author Carlos Frutos <carlos@kiwing.it>
 */
class Configuration
{
    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Set repository factory
     *
     * @param RepositoryFactoryInterface $repositoryFactory Repository factory
     *
     * @return void
     */
    public function setRepositoryFactory(RepositoryFactoryInterface $repositoryFactory)
    {
        $this->attributes['repositoryFactory'] = $repositoryFactory;
    }

    /**
     * Get repository factory
     *
     * @return RepositoryFactoryInterface
     */
    public function getRepositoryFactory()
    {
        return isset($this->attributes['repositoryFactory']) ?
            $this->attributes['repositoryFactory'] :
            new DefaultRepositoryFactory();
    }

    /**
     * Get class name of metadata factory
     *
     * @todo setter
     *
     * @return string
     */
    public function getClassMetadataFactoryName()
    {
        if (!isset($this->attributes['classMetadataFactoryName'])) {
            $this->attributes['classMetadataFactoryName'] = ClassMetadataFactory::class;
        }

        return $this->attributes['classMetadataFactoryName'];
    }

    /**
     * Sets the cache driver implementation that is used for metadata caching.
     *
     * @param MappingDriver $driverImpl
     *
     * @return void
     *
     * @todo Force parameter to be a Closure to ensure lazy evaluation
     *       (as soon as a metadata cache is in effect, the driver never needs to initialize).
     */
    public function setMetadataDriverImpl(MappingDriver $driverImpl)
    {
        $this->attributes['metadataDriverImpl'] = $driverImpl;
    }

    /**
     * Gets the cache driver implementation that is used for the mapping metadata.
     *
     * @return MappingDriver|null
     */
    public function getMetadataDriverImpl()
    {
        return isset($this->attributes['metadataDriverImpl'])
            ? $this->attributes['metadataDriverImpl']
            : null;
    }
}