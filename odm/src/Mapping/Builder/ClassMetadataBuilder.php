<?php
namespace Zitarrosa\ODM\Mapping\Builder;

use Zitarrosa\ODM\Mapping\ClassMetadata;

/**
 * Class metadata builder
 *
 * @author Carlos Frutos <carlos@kiwing.it>
 */
class ClassMetadataBuilder
{
    /**
     * @var ClassMetadata
     */
    protected $metadata;

    /**
     * Creates a new metadata builder
     *
     * @param ClassMetadata $metadata Metadata
     */
    public function __construct(ClassMetadata $metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * Sets the document name
     *
     * @param string $name Name
     *
     * @return self
     */
    public function document($name)
    {
        $this->metadata->setDocument($name);

        return $this;
    }

    /**
     * Adds field
     *
     * @param string $name
     * @param string $type
     * @param array  $mapping
     *
     * @return self
     */
    public function addField($name, $type, array $mapping = [])
    {
        $mapping['fieldName'] = (string) $name;
        $mapping['type'] = (string) $type;

        $this->metadata->mapField($mapping);

        return $this;
    }
}