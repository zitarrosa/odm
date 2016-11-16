<?php
namespace Flaubert\Persistence\Elastic\ODM\Expressions;

use PhpInterop\Objects\IArrayable;

/**
 * @author Carlos Frutos <carlos@kiwing.it>
 */
class Expression implements IArrayable
{
    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        throw new \Exception('toArray must be abstract hre');
    }
}