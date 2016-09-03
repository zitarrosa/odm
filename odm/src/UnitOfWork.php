<?php
namespace Zitarrosa\ODM;

/**
 * Unit of work tracks changes in an object-level
 *
 * @author Carlos Frutos <carlos@kiwing.it>
 */
class UnitOfWork
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
}