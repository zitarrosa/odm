<?php
namespace Zitarrosa\ODM\Mapping;

use Doctrine\Common\Persistence\Mapping\ClassMetadata as ClassMetadataInterface;
use Doctrine\Common\Persistence\Mapping\ReflectionService;

/**
 * Class metadata
 *
 * @author Carlos Frutos <carlos@kiwing.it>
 */
class ClassMetadata implements ClassMetadataInterface
{
    /**
     * READ-ONLY: The name of the entity class.
     *
     * @var string
     */
    public $name;

    /**
     * READ-ONLY: The name of the entity class that is at the root of the mapped entity inheritance
     * hierarchy. If the entity is not part of a mapped inheritance hierarchy this is the same
     * as {@link $name}.
     *
     * @var string
     */
    public $rootEntityName;

    /**
     * READ-ONLY
     *
     * Keys are field names
     *
     * - <b>fieldName</b> (string)
     * The name of the field in the entity.
     *
     * - <b>type</b> (string)
     * The type name of the mapped field.
     *
     * - <b>mappedName</b> (string)
     * Optional. Mapped field name. Defaults to the field name.
     *
     *
     * @var array
     */
    public $fieldMappings = [];

    /**
     * READ-ONLY: An array of field names. Used to look up field names from column names.
     * Keys are mapped field names and values are field names.
     * This is the reverse lookup map of $mappedFieldNames.
     *
     * @var array
     */
    public $fieldNames = [];

    /**
     * READ-ONLY: A map of field names to column names. Keys are field names and values mapped field names.
     * Used to look up mapped field names from field names.
     * This is the reverse lookup map of $fieldNames.
     *
     * @var array
     *
     * @todo We could get rid of this array by just using $fieldMappings[$fieldName]['columnName'].
     */
    public $mappedFieldNames = [];

    /**
     * @todo
     * @var ReflectionClass
     */
    public $reflClass;

    /**
     * READ-ONLY: Document name
     *
     * @param string
     */
    public $document;

    /**
     * Initializes a new ClassMetadata instance
     *
     * @param string $entityName Entity name
     */
    public function __construct($entityName)
    {
        $this->name = (string) $entityName;
        $this->rootEntityName = $this->entityName;
    }

    /**
     * Restores some state that can not be serialized/unserialized.
     *
     * @param ReflectionService $reflService
     *
     * @return void
     */
    public function wakeupReflection(ReflectionService $reflService)
    {
        $this->reflClass = $reflService->getClass($this->name);

        /** @todo */
    }

    /**
     * Gets the fully-qualified class name of this persistent class.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the mapped identifier field name.
     *
     * The returned structure is an array of the identifier field names.
     *
     * @return array
     */
    public function getIdentifier()
    {

    }

    /**
     * Gets the ReflectionClass instance for this mapped class.
     *
     * @return \ReflectionClass
     */
    public function getReflectionClass()
    {

    }

    /**
     * Checks if the given field name is a mapped identifier for this class.
     *
     * @param string $fieldName
     *
     * @return boolean
     */
    public function isIdentifier($fieldName)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function hasField($fieldName)
    {
        return isset($this->fieldMappings[$fieldName]);
    }

    /**
     * Checks if the given field is a mapped association for this class.
     *
     * @param string $fieldName
     *
     * @return boolean
     */
    public function hasAssociation($fieldName)
    {

    }

    /**
     * Checks if the given field is a mapped single valued association for this class.
     *
     * @param string $fieldName
     *
     * @return boolean
     */
    public function isSingleValuedAssociation($fieldName)
    {

    }

    /**
     * Checks if the given field is a mapped collection valued association for this class.
     *
     * @param string $fieldName
     *
     * @return boolean
     */
    public function isCollectionValuedAssociation($fieldName)
    {

    }

    /**
     * A numerically indexed list of field names of this persistent class.
     *
     * This array includes identifier fields if present on this class.
     *
     * @return array
     */
    public function getFieldNames()
    {

    }

    /**
     * Returns an array of identifier field names numerically indexed.
     *
     * @return array
     */
    public function getIdentifierFieldNames()
    {

    }

    /**
     * Returns a numerically indexed list of association names of this persistent class.
     *
     * This array includes identifier associations if present on this class.
     *
     * @return array
     */
    public function getAssociationNames()
    {

    }

    /**
     * Returns a type name of this field.
     *
     * This type names can be implementation specific but should at least include the php types:
     * integer, string, boolean, float/double, datetime.
     *
     * @param string $fieldName
     *
     * @return string
     */
    public function getTypeOfField($fieldName)
    {

    }

    /**
     * Returns the target class name of the given association.
     *
     * @param string $assocName
     *
     * @return string
     */
    public function getAssociationTargetClass($assocName)
    {

    }

    /**
     * Checks if the association is the inverse side of a bidirectional association.
     *
     * @param string $assocName
     *
     * @return boolean
     */
    public function isAssociationInverseSide($assocName)
    {

    }

    /**
     * Returns the target field of the owning side of the association.
     *
     * @param string $assocName
     *
     * @return string
     */
    public function getAssociationMappedByTargetField($assocName)
    {

    }

    /**
     * Returns the identifier of this object as an array with field name as key.
     *
     * Has to return an empty array if no identifier isset.
     *
     * @param object $object
     *
     * @return array
     */
    public function getIdentifierValues($object)
    {

    }

    /**
     * Set document name
     *
     * @param string $document Document name
     *
     * @return void
     */
    public function setDocument($document)
    {
        $this->document = (string) $document;
    }

    /**
     * Adds a mapped field to the class.
     *
     * @param array $mapping The field mapping.
     *
     * @return void
     */
    public function mapField(array $mapping)
    {
        $this->validateAndCompleteFieldMapping($mapping);

        $this->fieldMappings[$mapping['fieldName']] = $mapping;
    }

    /**
     * @return void
     */
    protected function validateAndCompleteFieldMapping(array &$mapping)
    {
        if (!empty($mapping['id'])) {
            $this->identifier = $mapping['fieldName'];
        }

        if (empty($mapping['mappedFieldName'])) {
            $mapping['mappedFieldName'] = $mapping['fieldName'];
        }

        $this->mappedFieldNames[$mapping['fieldName']] = $mapping['mappedFieldName'];

        $this->fieldNames[$mapping['mappedFieldName']] = $mapping['fieldName'];
    }
}