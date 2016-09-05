<?php
namespace Zitarrosa\ODM\Tests;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Zitarrosa\ODM\EntityManager;
use Zitarrosa\Common\Utils\Objects\MemberAccesor;
use Zitarrosa\ODM\Mapping\ClassMetadataFactory;
use Zitarrosa\ODM\Repository\RepositoryFactoryInterface;
use Zitarrosa\ODM\EntityRepository;
use Mockery as m;

class EntityManagerTest extends ODMTest
{
    public function testGetClassMetadata()
    {
        $entityManager = $this->newInstanceWithoutConstructor(EntityManager::class);

        $metadata = m::mock(ClassMetadata::class);

        $mockedMetadataFactory = m::mock(ClassMetadataFactory::class);
        $mockedMetadataFactory->shouldReceive('getMetadataFor')
            ->with('ClassName')
            ->once()
            ->andReturn($metadata);

        MemberAccesor::set($entityManager, 'metadataFactory', $mockedMetadataFactory);

        $result = $entityManager->getClassMetadata('ClassName');
        $this->assertEquals($metadata, $result);
    }

    public function testGetRepository()
    {
        $entityManager = $this->newInstanceWithoutConstructor(EntityManager::class);

        $mockedRepo = m::mock(EntityRepository::class);

        $mockedRepositoryFactory = m::mock(RepositoryFactoryInterface::class);
        $mockedRepositoryFactory->shouldReceive('getRepository')
            ->with($entityManager, 'Namespace\\To\\EntityClass')
            ->once()
            ->andReturn($mockedRepo);

        MemberAccesor::set($entityManager, 'repositoryFactory', $mockedRepositoryFactory);

        $result = $entityManager->getRepository('Namespace\\To\\EntityClass');
        $this->assertEquals($mockedRepo, $result);
    }
}