<?php
namespace VeeWee\Reflecta\Reflect;

use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;

/**
 * @psalm-internal VeeWee\Reflecta
 *
 * @param object|class-string $objectOrClassName
 * @return \ReflectionProperty
 *
 * @throws UnreflectableException
 */
function reflect_property(mixed $objectOrClassName, string $property): \ReflectionProperty
{
    $reflection = is_string($objectOrClassName) ? reflect_class($objectOrClassName) : reflect_object($objectOrClassName);

    try {
        return $reflection->getProperty($property);
    } catch (\ReflectionException) {
        throw UnreflectableException::unknownProperty(
            is_string($objectOrClassName) ? $objectOrClassName : get_debug_type($objectOrClassName),
            $property
        );
    }
}
