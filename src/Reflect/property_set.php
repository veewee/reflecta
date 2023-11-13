<?php

namespace VeeWee\Reflecta\Reflect;

use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;

/**
 * @throws UnreflectableException
 *
 * @template T of object
 * @param T $object
 * @return T
 */
function property_set(object $object, string $name, mixed $value): object {

    $propertyInfo = reflect_property($object, $name);

    $new = clone $object;
    $propertyInfo->setValue($new, $value);

    return $new;
}
