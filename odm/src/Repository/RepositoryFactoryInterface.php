<?php
namespace Zitarrosa\ODM\Repository;

use Zitarrosa\ODM\EntityManagerInterface;

/**
 * Repository factory interface
 *
 * @author Carlos Frutos <carlos@kiwing.it>
 */
interface RepositoryFactoryInterface
{
    /**
     * Gets the repository for an entity class.
     *
     * @param EntityManagerInterface    $entityManager The EntityManager instance.
     * @param string                    $entityName    The name of the entity.
     *
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository(EntityManagerInterface $entityManager, $entityName);
}