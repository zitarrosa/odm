<?php
namespace Flaubert\Persistence\Elastic\Normalization;

use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Flaubert\Common\Objects\PropertyReader;
use Flaubert\Common\Objects\PropertyWriter;

class AbstractNormalizer extends PropertyNormalizer
{
    /**
     * Callbacks to apply on denormalization
     *
     * @var array<Callable>
     */
    protected $denormalizeCallbacks = [];

    /**
     * {@inheritdoc}
     */
    public function __construct(ClassMetadataFactoryInterface $classMetadataFactory = null, NameConverterInterface $nameConverter = null)
    {
        parent::__construct($classMetadataFactory, $nameConverter);
        $this->init();
    }

    /**
     * Init stuff
     *
     * @return void
     */
    protected function init()
    {
        //Do nothing
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $result = parent::denormalize($data, $class, $format, $context);

        foreach ($this->denormalizeCallbacks as $propertyName => $callback) {
            $propertyValue = $callback(PropertyReader::read($result, $propertyName));
            PropertyWriter::write($result, $propertyName, $propertyValue);
        }

        return $result;
    }

    /**
     * @return self
     */
    protected function addDenormalizeCallback($propertyName, Callable $callback)
    {
        $this->denormalizeCallbacks[$propertyName] = $callback;

        return $this;
    }
}