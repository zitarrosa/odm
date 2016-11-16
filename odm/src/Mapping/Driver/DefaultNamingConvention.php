<?php
namespace Flaubert\Persistence\Elastic\Mapping\Driver;

/**
 * Default naming convention
 *
 * @author Carlos Frutos <carlos@kiwing.it>
 */
class DefaultNamingConvention implements INamingConvention
{
    const MAPPING_SUFFIX = 'Mapping';

    /**
     * @var string
     */
    protected $entityNamespace;

    /**
     * @var string
     */
    protected $mappingNamespace;

    /**
     * @param string $entityNamespace Entity namespace
     * @param string $mappingNamespace Mapping namespace
     */
    public function __construct($entityNamespace, $mappingNamespace)
    {
        $this->entityNamespace = (string) $entityNamespace;
        $this->mappingNamespace = (string) $mappingNamespace;
    }

    /**
     * {@inheritdoc}
     */
    public function fromClassNameToMappingClass($className)
    {
        $entityName = end(explode('\\', $className));

        return $this->mappingNamespace . '\\' . $entityName . static::MAPPING_SUFFIX;
    }

    /**
     * {@inheritdoc}
     */
    public function fromMappingClassToClassName($mappingClass)
    {
        $mappingClassName = end(explode('\\', $mappingClass));
        $entityName = str_replace(static::MAPPING_SUFFIX, '', $mappingClassName);

        return $this->entityNamespace . '\\' . $entityName;
    }
}