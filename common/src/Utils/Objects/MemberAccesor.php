<?php
namespace Zitarrosa\Common\Utils\Objects;

use ReflectionClass;

/**
 * Allows to access protected/private object members
 *
 * @author Carlos Frutos <carlos@kiwing.it>
 */
class MemberAccesor
{
    /**
     * Get property of a subject class or object
     *
     * @param   string|object   $subject        Subject object or class
     * @param   string          $propertyName   Property name
     *
     * @return mixed
     */
    public static function get($subject, $propertyName)
    {
        $property = static::getPropertyOf($subject, $propertyName);

        return $property->isStatic() ?
            $property->getValue() :
            $property->getValue($subject);
    }

    /**
     * Set property to a subject class or object
     *
     * @param   string|object   $subject        Subject object or class
     * @param   string          $propertyName   Property name
     * @param   mixed           $value          Value
     *
     * @return void
     */
    public static function set($subject, $propertyName, $value)
    {
        $property = static::getPropertyOf($subject, $propertyName);

        if ($property->isStatic()) {
            $property->setValue($value);
        } else {
            $property->setValue($subject, $value);
        }
    }

    /**
     * Get reflection property of a subject
     *
     * @param   string|object   $subject        Subject object or class
     * @param   string          $propertyName   Property name
     *
     * @return ReflectionProperty
     */
    private static function getPropertyOf($subject, $propertyName)
    {
        if (is_string($subject) && class_exists($subject)) {
            $class = new ReflectionClass($subject);
        } else {
            $class = new ReflectionClass(get_class($subject));
        }

        $property = $class->getProperty((string) $propertyName);
        $property->setAccessible(true);

        return $property;
    }
}