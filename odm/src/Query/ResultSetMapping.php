<?php
namespace Flaubert\Persistence\Elastic\ODM\Query;

class ResultSetMapping
{
    /**
     * Whether the result is mixed (contains scalar values together with field values).
     *
     * @ignore
     * @var boolean
     */
    public $isMixed = false;

    /**
     * Maps alias names to class names.
     *
     * @ignore
     * @var array
     */
    public $aliasMap = [];

    /**
     * Maps mapped field names in the result set to field names for each class.
     *
     * @ignore
     * @var array
     */
    public $fieldMappings = [];

    /**
     * Maps mapped field names in the result set to the alias/field name to use in the mapped result.
     *
     * @ignore
     * @var array
     */
    public $scalarMappings = [];

    /**
     * Maps mapped fields names in the result set to the alias/field type to use in the mapped result.
     *
     * @ignore
     * @var array
     */
    public $typeMappings = [];

    /**
     * Maps mapped field names in the result set to the alias name to use in the mapped result.
     *
     * @ignore
     * @var array
     */
    public $entityMappings = [];

    /**
     * Maps mapped fields names of meta columns (foreign keys, discriminator columns, ...) to field names.
     *
     * @ignore
     * @var array
     */
    public $metaMappings = [];

    /**
     * List of mapped fields in the result set that are used as discriminator fields.
     *
     * @ignore
     * @var array
     */
    public $discriminatorMappedFields = [];

    /**
     * Maps mapped fields names in the result set to the alias they belong to.
     *
     * @ignore
     * @var array
     */
    public $mappedFieldOwnerMap = [];

    /**
     * Map from mapped field names to class names that declare the field the mapped field is mapped to.
     *
     * @ignore
     * @var array
     */
    public $declaringClasses = [];

    /**
     * This is necessary to hydrate derivate foreign keys correctly.
     *
     * @var array
     */
    public $isIdentifierMappedField = [];

    /**
     * Adds an entity result to this ResultSetMapping.
     *
     * @param string $class            The class name of the entity.
     * @param string $alias            The alias for the class. The alias must be unique among all entity
     *                                 results or joined entity results within this ResultSetMapping.
     * @param string|null $resultAlias The result alias with which the entity result should be
     *                                 placed in the result structure.
     *
     * @return self This ResultSetMapping instance.
     */
    public function addEntityResult($class, $alias, $resultAlias = null)
    {
        $this->aliasMap[$alias] = $class;
        $this->entityMappings[$alias] = $resultAlias;

        if ($resultAlias !== null) {
            $this->isMixed = true;
        }

        return $this;
    }

    /**
     * Adds a field to the result that belongs to an entity or joined entity.
     *
     * @param string      $alias          The alias of the root entity or joined entity to which the field belongs.
     * @param string      $mappedFieldName     The name of the mapped field in the DSL result set.
     * @param string      $fieldName      The name of the field on the declaring class.
     * @param string|null $declaringClass The name of the class that declares/owns the specified field.
     *                                    When $alias refers to a superclass in a mapped hierarchy but
     *                                    the field $fieldName is defined on a subclass, specify that here.
     *                                    If not specified, the field is assumed to belong to the class
     *                                    designated by $alias.
     *
     * @return self This ResultSetMapping instance.
     */
    public function addField($alias, $mappedFieldName, $fieldName, $declaringClass = null)
    {
        // column name (in result set) => field name
        $this->fieldMappings[$mappedFieldName] = $fieldName;
        // column name => alias of owner
        $this->mappedFieldOwnerMap[$mappedFieldName] = $alias;
        // field name => class name of declaring class
        $this->declaringClasses[$mappedFieldName] = $declaringClass ?: $this->aliasMap[$alias];

        if ( ! $this->isMixed && $this->scalarMappings) {
            $this->isMixed = true;
        }

        return $this;
    }

    /**
     * Adds a discriminator mapped field for an entity result or joined entity result.
     * The discriminator mapped field will be used to determine the concrete class name to
     * instantiate.
     *
     * @param string $alias       The alias of the entity result or joined entity result the discriminator
     *                            mapped field should be used for.
     * @param string $discrMappedField The name of the discriminator mapped field in the DSL result set.
     *
     * @return self This ResultSetMapping instance.
     */
    public function addDiscriminatorMappedField($alias, $discrMappedField)
    {
        $this->discriminatorMappedFields[$alias] = $discrMappedField;
        $this->mappedFieldOwnerMap[$discrMappedField] = $alias;

        return $this;
    }

    /**
     * Adds a meta mapped field (foreign key or discriminator column) to the result set.
     *
     * @param string $alias                 The result alias with which the meta result should be placed in the result structure.
     * @param string $mappedFieldName            The name of the mapped field in the DSL result set.
     * @param string $fieldName             The name of the field on the declaring class.
     * @param bool   $isIdentifier
     * @param string $type                  The field type
     *
     * @return ResultSetMapping This ResultSetMapping instance.
     */
    public function addMetaResult($alias, $mappedFieldName, $fieldName, $isIdentifier = false, $type = null)
    {
        $this->metaMappings[$mappedFieldName] = $fieldName;
        $this->mappedFieldOwnerMap[$mappedFieldName] = $alias;

        if ($isIdentifier) {
            $this->isIdentifierMappedField[$alias][$mappedFieldName] = true;
        }

        if ($type) {
            $this->typeMappings[$columnName] = $type;
        }

        return $this;
    }
}