<?php
namespace VeeWee\Reflecta\Reflect;

use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;

/**
 * @psalm-internal VeeWee\Reflecta
 *
 * @param object $object
 * @return \ReflectionObject
 *
 * @throws UnreflectableException
 */
function reflect_object(object $object): \ReflectionObject
{
    try {
        return new \ReflectionObject($object);
    } catch (\Throwable $previous) {
        throw UnreflectableException::unknownClass(get_debug_type($object), $previous);
    }
}

