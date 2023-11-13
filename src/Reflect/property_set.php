<?php

namespace VeeWee\Reflecta\Reflect;

use VeeWee\Reflecta\Exception\CloneException;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use function Psl\Result\try_catch;

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

    $new = try_catch(
        static fn() => clone $object,
        static fn(): never => throw CloneException::impossibleToClone($object)
    );

    try_catch(
        static fn() => $propertyInfo->setValue($new, $value),
        static fn(): never => throw UnreflectableException::readonlyProperty(get_debug_type($object), $name)
    );

    return $new;
}
