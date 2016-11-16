<?php
namespace Flaubert\Persistence\Elastic\Mapping\Driver;

use Doctrine\Common\Persistence\Mapping\Driver\FileLocator as DoctrineFileLocator;

class DefaultFileLocator implements DoctrineFileLocator
{
    /**
     * @var array
     */
    protected $settings;

    public function __construct(array $settings = [])
    {
        $this->settings = $settings;
    }

    /**
     * Locates mapping file for the given class name.
     *
     * @param string $className
     *
     * @return string
     */
    public function findMappingFile($className)
    {

    }

    /**
     * Gets all class names that are found with this file locator.
     *
     * @param string $globalBasename Passed to allow excluding the basename.
     *
     * @return array
     */
    public function getAllClassNames($globalBasename)
    {

    }

    /**
     * Checks if a file can be found for this class name.
     *
     * @param string $className
     *
     * @return bool
     */
    public function fileExists($className)
    {

    }

    /**
     * Gets all the paths that this file locator looks for mapping files.
     *
     * @return array
     */
    public function getPaths()
    {

    }

    /**
     * Gets the file extension that mapping files are suffixed with.
     *
     * @return string
     */
    public function getFileExtension()
    {

    }
}