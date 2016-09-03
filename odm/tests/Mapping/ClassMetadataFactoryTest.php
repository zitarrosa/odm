<?php
namespace Zitarrosa\ODM\Tests\Mapping;

use Zitarrosa\ODM\EntityManagerInterface;
use Zitarrosa\ODM\Mapping\ClassMetadataFactory;
use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * Tests for Zitarrosa\ODM\Mapping\ClassMetadataFactory class
 *
 * @author Carlos Frutos <carlos@kiwing.it>
 */
class ClassMetadataFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ClassMetadataFactory
     */
    private $sut;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->sut = new ClassMetadataFactory();
    }

    public function testAcceptsEntityManagerInterfaceInstances()
    {
        $em = m::mock(EntityManagerInterface::class);
        $this->sut->setEntityManager($em);

        $this->assertAttributeSame($em, 'em', $this->sut);
    }
}