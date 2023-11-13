<?php

namespace VeeWee\Reflecta\Reflect;

use VeeWee\Reflecta\Exception\UnreflectableException;

/**
 * @throws UnreflectableException
 *
 * @template T of object
 * @param T $object
 * @return T
 */
function property_set(object $object, string $name, mixed $value): object {
    try {
        $classInfo = new \ReflectionObject($object);
        $propInfo = $classInfo->getProperty($name);
    } catch (\ReflectionException $e) {
        throw UnreflectableException::unknownProperty(get_debug_type($object), $name);
    }

    $new = clone $object;
    $propInfo->setValue($new, $value);

    return $new;
}
