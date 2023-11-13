<?php

namespace VeeWee\Reflecta\Reflect;

use VeeWee\Reflecta\Exception\UnreflectableException;

/**
 * @throws UnreflectableException
 */
function property_get(object $object, string $name): mixed {
    try {
        $classInfo = new \ReflectionObject($object);
        $propInfo = $classInfo->getProperty($name);
    } catch (\ReflectionException $e) {
        throw UnreflectableException::unknownProperty(get_debug_type($object), $name);
    }

    return $propInfo->getValue($object);
}
