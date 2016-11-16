<?php
namespace Flaubert\Persistence\Elastic\Mapping\Driver;

use Exception;
use Flaubert\Common\Objects\ClassFinder as FlaubertClassFinder;
use Flaubert\Persistence\Elastic\Mapping\AbstractMapping;

/**
 * @author Carlos Frutos <carlos@kiwing.it>
 */
class FlaubertFileLocator extends DefaultFileLocator
{
    /**
     * @var FlaubertClassFinder
     */
    protected $classFinder;

    /**
     * @var string
     */
    protected $mappingPath;

    /**
     * @var INamingConvention
     */
    protected $namingConvention;

    /**
     * @param FlaubertClassFinder $classFinder Class finder
     * @param array $settings Settings
     */
    public function __construct(FlaubertClassFinder $classFinder, array $settings)
    {
        $this->classFinder = $classFinder;

        $this->mappingPath = (string) $settings['mappingPath'];

        if (
            empty($settings['namingConvention']) ||
            !($settings['namingConvention'] instanceof INamingConvetion)
        ) {
            $settings['namingConvention'] = new DefaultNamingConvention(
                $settings['entityNamespace'],
                $settings['mappingNamespace']
            );
        }
        $this->namingConvention = $settings['namingConvention'];
    }

    /**
     * {@inheritdoc}
     */
    public function findMappingFile($className)
    {
        $mappingClass = $this->namingConvention->fromClassNameToMappingClass($className);

        $baseMappingClass = end(explode('\\', $mappingClass));

        return $this->mappingPath . '/' . $baseMappingClass . '.php';
    }

    /**
     * {@inheritdoc}
     */
    public function getAllClassNames($globalBasename)
    {
        $foundClasses = $this->classFinder->findClasses($this->mappingPath, [
            'mustBeA' => AbstractMapping::class
        ]);
        $namingConvention = $this->namingConvention;

        $entityClasses = array_map(function($mappingClass) use ($namingConvention) {
            return $namingConvention->fromMappingClassToClassName($mappingClass);
        }, $foundClasses);

        return $entityClasses;
    }

    /**
     * {@inheritdoc}
     */
    public function fileExists($className)
    {
        throw new Exception('Not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function getPaths()
    {
        return [$this->mappingPath];
    }

    /**
     * {@inheritdoc}
     */
    public function getFileExtension()
    {
        return '.php';
    }
}