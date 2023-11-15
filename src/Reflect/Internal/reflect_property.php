<?php declare(strict_types=1);
namespace VeeWee\Reflecta\Reflect;

use ReflectionProperty;
use Throwable;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;

/**
 * @psalm-internal VeeWee\Reflecta
 *
 * @param object|class-string $objectOrClassName
 *
 * @throws UnreflectableException
 */
function reflect_property(mixed $objectOrClassName, string $property): ReflectionProperty
{
    $reflection = is_string($objectOrClassName) ? reflect_class($objectOrClassName) : reflect_object($objectOrClassName);

    try {
        return $reflection->getProperty($property);
    } catch (Throwable $previous) {
        throw UnreflectableException::unknownProperty(
            is_string($objectOrClassName) ? $objectOrClassName : get_debug_type($objectOrClassName),
            $property,
            $previous
        );
    }
}
