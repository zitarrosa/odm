<?php
namespace Zitarrosa\Example\Mapping;

use Zitarrosa\ODM\Mapping\Driver\PHP\AbstractMapper;

class StudentMapping extends AbstractMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($mb)
    {
        $mb->document('students')
           ->addField('firstName', 'string')
           ->addField('lastName', 'string');
    }
}