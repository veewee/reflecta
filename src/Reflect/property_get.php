<?php declare(strict_types=1);

namespace VeeWee\Reflecta\Reflect;

use ReflectionProperty;
use Throwable;
use VeeWee\Reflecta\Reflect\Exception\UnreflectableException;
use VeeWee\Reflecta\Reflect\Type\ReflectedClass;

/**
 * @throws UnreflectableException
 */
function property_get(object $object, string $name): mixed
{
    $class = ReflectedClass::fromObject($object);
    $property = $class->property($name);

    try {
        return $property->apply(
            static fn (ReflectionProperty $reflectionProperty): mixed => $reflectionProperty->getValue($object)
        );
    } catch (Throwable $previous) {
        throw UnreflectableException::unreadableProperty($class->fullName(), $name, $previous);
    }
}
