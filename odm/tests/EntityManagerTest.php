<?php
namespace Zitarrosa\ODM\Tests;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Zitarrosa\ODM\EntityManager;
use Zitarrosa\Common\Utils\Objects\MemberAccesor;
use Zitarrosa\ODM\Mapping\ClassMetadataFactory;
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
}