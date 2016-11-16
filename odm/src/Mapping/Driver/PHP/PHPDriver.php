<?php
namespace Zitarrosa\ODM\Mapping\Driver\PHP;

use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\Common\Persistence\Mapping\Driver\FileLocator;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;


/**
 * The PHPDriver includes php files which just populate ElasticMetadata
 * instances with plain PHP code.
 *
 * @author Carlos Frutos <carlos@kiwing.it>
 */
class PHPDriver implements MappingDriver
{
    /**
     * @var Doctrine\Common\Persistence\Mapping\Driver\FileLocator
     */
    protected $locator;

    /**
     * @var INamingConvention
     */
    protected $namingConvention;

    /**
     * Creates a new instance
     *
     * @param FileLocator $locator Custom file locator
     */
    public function __construct(FileLocator $locator, INamingConvention $namingConvention)
    {
        $this->locator = $locator;
        $this->namingConvention = $namingConvention;
    }

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass($className, ClassMetadata $metadata)
    {
        $mappingClass = $this->namingConvention->fromClassNameToMappingClass($className);
        $metadataBuilder = new $mappingClass($metadata);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllClassNames()
    {
        return $this->locator->getAllClassNames('');
    }

    /**
     * {@inheritdoc}
     */
    public function isTransient($className)
    {
        /**
         * @todo
         */
        return false;
    }
}