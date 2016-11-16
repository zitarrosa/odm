<?php
namespace Flaubert\Persistence\Elastic\Mapping\Builder;

/**
 * Builds elastic field
 *
 * @author Carlos Frutos <carlos@kiwing.it>
 */
class ElasticFieldBuilder
{
    /**
     * @var ElasticMetadataBuilder
     */
    protected $metadataBuilder;

    /**
     * @var array
     */
    protected $mapping;

    /**
     * Creates new instance
     *
     * @param ElasticMetadataBuilder $metadataBuilder Metadata builder
     * @param array $mapping Mapping
     */
    public function __construct(ElasticMetadataBuilder $metadataBuilder, array $mapping = [])
    {
        $this->metadataBuilder = $metadataBuilder;
        $this->mapping = $mapping;
    }

    /**
     * Sets the mapped field name
     *
     * @param string $name Mapped field name
     *
     * @return self This builder
     */
    public function mappedFieldName($name)
    {
        $this->mapping['mappedFieldName'] = (string) $name;

        return $this;
    }

    /**
     * Mark this field as identifier
     *
     * @return self This builder
     */
    public function isIdentifier()
    {
        $this->mapping['id'] = true;

        /**
         * Forces mapped field name to standard _id
         */
        $this->mapping['mappedFieldName'] = '_id';

        return $this;
    }

    /**
     * Builds the field
     *
     * @return ElasticMetadataBuilder
     */
    public function build()
    {
        $this->metadataBuilder->getClassMetadata()->mapField($this->mapping);

        return $this->metadataBuilder;
    }
}