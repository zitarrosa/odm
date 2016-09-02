<?php
namespace Zitarrosa\ODM;

/**
 * Facade of all ODM subsystems
 *
 * @author Carlos Frutos <carlos@kiwing.it>
 */
final class EntityManager implements EntityManagerInterface
{
    /**
     * {@inheritdoc}
     */
    public function find($className, $id);

    /**
     * {@inheritdoc}
     */
    public function persist($object);

    /**
     * {@inheritdoc}
     */
    public function remove($object);

    /**
     * {@inheritdoc}
     */
    public function merge($object);

    /**
     * {@inheritdoc}
     */
    public function clear($objectName = null);

    /**
     * {@inheritdoc}
     */
    public function detach($object);

    /**
     * {@inheritdoc}
     */
    public function refresh($object);

    /**
     * {@inheritdoc}
     */
    public function flush();

    /**
     * {@inheritdoc}
     */
    public function getRepository($className);

    /**
     * {@inheritdoc}
     */
    public function getClassMetadata($className);

    /**
     * {@inheritdoc}
     */
    public function getMetadataFactory();

    /**
     * {@inheritdoc}
     */
    public function initializeObject($obj);

    /**
     * {@inheritdoc}
     */
    public function contains($object);
}