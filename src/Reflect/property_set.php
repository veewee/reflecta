<?php

namespace VeeWee\Reflecta\Reflect;

use VeeWee\Reflecta\Exception\CloneException;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;

/**
 * @throws UnreflectableException
 * @throws CloneException
 *
 * @template T of object
 * @param T $object
 * @return T
 */
function property_set(object $object, string $name, mixed $value): object {

    $propertyInfo = reflect_property($object, $name);

    try {
        $new = clone $object;
    } catch (\Throwable $previous) {
        throw CloneException::impossibleToClone($object, $previous);
    }

    try {
        $propertyInfo->setValue($new, $value);
    } catch (\Throwable $previous) {
        throw UnreflectableException::unwritableProperty(get_debug_type($object), $name, $value, $previous);
    }

    return $new;
}
