<?php
namespace Zitarrosa\ODM\Tests;

use PHPUnit_Framework_TestCase;
use ReflectionClass;

/**
 * ODM Test
 */
abstract class ODMTest extends PHPUnit_Framework_TestCase
{
    /**
     * Creates a new class instance without invoking constructor
     *
     * @param string $class Class name
     *
     * @return object
     */
    public function newInstanceWithoutConstructor($class)
    {
        $reflClass = new ReflectionClass($class);

        return $reflClass->newInstanceWithoutConstructor();
    }
}