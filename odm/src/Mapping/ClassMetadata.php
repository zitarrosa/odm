<?php
namespace Zitarrosa\ODM\Mapping;

use Doctrine\Common\Persistence\Mapping\ClassMetadata as ClassMetadataInterface;
use Doctrine\Common\Persistence\Mapping\ReflectionService;

/**
 * Class metadata
 *
 * @author Carlos Frutos <carlos@kiwing.it>
 */
class ClassMetadata implements ClassMetadataInterface
{
    /**
     * Restores some state that can not be serialized/unserialized.
     *
     * @param ReflectionService $reflService
     *
     * @return void
     */
    public function wakeupReflection(ReflectionService$reflService)
    {

    }
}