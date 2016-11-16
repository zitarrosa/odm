<?php
namespace Flaubert\Persistence\Elastic\Mapping\Driver;

/**
 * Naming convention for mappers
 *
 * @author Carlos Frutos <carlos@kiwing.it>
 */
interface INamingConvention
{
    /**
     * @param string $className Class name
     *
     * @return string Mapping class
     */
    public function fromClassNameToMappingClass($className);

    /**
     * @param string $mappingClass Mapping class
     *
     * @return string Entity class name
     */
    public function fromMappingClassToClassName($mappingClass);
}