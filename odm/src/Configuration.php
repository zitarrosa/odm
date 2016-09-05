<?php
namespace Zitarrosa\ODM;

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
}