<?php
namespace Zitarrosa\ODM\Mapping\Driver\PHP;

use Zitarrosa\ODM\Mapping\Builder\ClassMetadataBuilder;

/**
 * Abstract PHP mapper
 *
 * @author Carlos Frutos <carlos@kiwing.it>
 */
abstract class AbstractMapper
{
    /**
     * Map class
     *
     * @param ClassMetadataBuilder $mb Metadata builder
     *
     * @return void
     */
    public abstract function map(ClassMetadataBuilder $mb);
}